<?php

namespace Modules\TenantCore\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Core\Models\Account;
use Modules\Core\Models\Tenant;
use Modules\Core\Services\ContextCache;
use Modules\TenantCore\Models\Branch;
use Symfony\Component\HttpFoundation\Response;

class ResolveBranch
{
    public function __construct(private ContextCache $contextCache) {}

    public function handle(Request $request, Closure $next, string $selection = 'optional'): Response
    {
        if ($request->attributes->get('application_provider') !== 'users' || $request->routeIs('auth.*')) {
            return $next($request);
        }

        $user = $request->user('web');

        if (! $user instanceof Account || ! $user->matchesApplication()) {
            return $next($request);
        }

        $multipleBranches = (bool) Tenant::current()?->has_branches;

        if (! $request->attributes->has('active_branch')) {
            $this->resolve($request, $user, $multipleBranches);
        }

        $branch = $request->attributes->get('active_branch');
        $hasBranches = $request->attributes->get('branches_available');

        if ($request->routeIs('tenantcore.branch-dashboard.index') && (! $multipleBranches || $user->branch_id !== null || ! $hasBranches)) {
            return redirect()->route('home');
        }

        if ($branch === null && $selection === 'required') {
            abort_if($request->expectsJson(), 409, 'Select a branch before continuing.');

            if ($hasBranches && $request->isMethod('GET')) {
                $request->session()->put('url.intended', $request->fullUrl());
            }

            return redirect()->route($hasBranches ? 'tenantcore.branch-dashboard.index' : 'home');
        }

        return $next($request);
    }

    private function resolve(Request $request, Account $user, bool $multipleBranches): void
    {
        $session = $request->session();

        if ($session->get('branch_user_id') !== $user->getAuthIdentifier()) {
            $session->forget(['branch_id', 'branch_user_id']);
        }

        $assigned = $user->branch_id !== null;
        $model = new Branch;
        $connection = $model->getConnectionName();

        if (! $assigned && ! $multipleBranches) {
            $attributes = $this->contextCache->remember($connection, 'branches', 'default',
                fn (): ?array => Branch::where('is_default', true)
                    ->first(['id', 'name'])
                    ?->getAttributes());
        } else {
            $branchId = $assigned ? $user->branch_id : $session->get('branch_id');
            $visibility = $multipleBranches ? 'selectable' : 'assigned';
            $attributes = $branchId === null ? null : $this->contextCache->remember($connection, 'branches', $visibility.':'.$branchId,
                fn (): ?array => Branch::whereKey($branchId)
                    ->when($multipleBranches, fn ($query) => $query->where('is_default', false))
                    ->first(['id', 'name'])
                    ?->getAttributes());
        }

        $branch = $attributes === null ? null : $model->newFromBuilder($attributes);
        abort_if($assigned && $branch === null, 403, 'Your assigned branch is unavailable. Contact your administrator.');
        $hasBranches = $branch !== null || $this->contextCache->remember($connection, 'branches', 'selectable-available',
            fn (): bool => Branch::where('is_default', false)->exists());

        if ($branch === null) {
            $session->forget(['branch_id', 'branch_user_id']);
        } else {
            $session->put(['branch_id' => $branch->getKey(), 'branch_user_id' => $user->getAuthIdentifier()]);
        }

        // Branch-owned queries use this validated context, never body input.
        $request->attributes->set('active_branch', $branch);
        $request->attributes->set('branch_id', $branch?->getKey());
        $request->attributes->set('branch_name', $multipleBranches ? $branch?->name : null);
        $request->attributes->set('branch_can_switch', $multipleBranches && ! $assigned && $hasBranches);
        $request->attributes->set('branches_available', $hasBranches);
    }
}
