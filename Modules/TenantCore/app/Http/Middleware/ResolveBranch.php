<?php

namespace Modules\TenantCore\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Core\Models\Account;
use Modules\Core\Services\AccessManager;
use Modules\Core\Services\ContextCache;
use Modules\TenantCore\Models\Branch;
use Symfony\Component\HttpFoundation\Response;

class ResolveBranch
{
    public function __construct(private AccessManager $access, private ContextCache $contextCache) {}

    public function handle(Request $request, Closure $next, string $selection = 'optional'): Response
    {
        if ($request->attributes->get('application_provider') !== 'users' || $request->routeIs('auth.*')) {
            return $next($request);
        }

        $user = $request->user('web');

        if (! $user instanceof Account || ! $this->access->actorMatchesContext($user)) {
            return $next($request);
        }

        if (! $request->attributes->has('active_branch')) {
            $this->resolve($request, $user);
        }

        $branch = $request->attributes->get('active_branch');
        $hasBranches = $request->attributes->get('branches_available');

        if ($request->routeIs('tenantcore.branch-dashboard.index') && ($user->branch_id !== null || ! $hasBranches)) {
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

    private function resolve(Request $request, Account $user): void
    {
        $session = $request->session();

        if ($session->get('branch_user_id') !== $user->getAuthIdentifier()) {
            $session->forget(['branch_id', 'branch_user_id']);
        }

        $assigned = $user->branch_id !== null;
        $branchId = $assigned ? $user->branch_id : $session->get('branch_id');
        $model = new Branch;
        $connection = $model->getConnectionName();
        $attributes = $branchId === null ? null : $this->contextCache->remember($connection, 'branches', $branchId,
            fn (): ?array => Branch::query()->find($branchId, ['id', 'name'])?->getAttributes());
        $branch = $attributes === null ? null : $model->newFromBuilder($attributes);
        abort_if($assigned && $branch === null, 403, 'Your assigned branch is unavailable. Contact your administrator.');
        $hasBranches = $branch !== null || $this->contextCache->remember($connection, 'branches', 'available', fn (): bool => Branch::query()->exists());

        if ($branch === null) {
            $session->forget(['branch_id', 'branch_user_id']);
        } else {
            $session->put(['branch_id' => $branch->getKey(), 'branch_user_id' => $user->getAuthIdentifier()]);
        }

        // Branch-owned queries use this validated context, never body input.
        $request->attributes->set('active_branch', $branch);
        $request->attributes->set('branch_id', $branch?->getKey());
        $request->attributes->set('branch_name', $branch?->name);
        $request->attributes->set('branch_can_switch', ! $assigned && $hasBranches);
        $request->attributes->set('branches_available', $hasBranches);

    }
}
