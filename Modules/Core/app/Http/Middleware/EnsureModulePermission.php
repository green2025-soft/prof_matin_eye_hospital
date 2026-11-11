<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Core\Services\AccessManager;
use Symfony\Component\HttpFoundation\Response;

class EnsureModulePermission
{
    public function __construct(private AccessManager $access) {}

    public function handle(Request $request, Closure $next, string $modulePermission): Response
    {
        $permissions = $request->attributes->get('navigation_permissions');

        // The route and its page controls reuse the same account-and-plan permissions.
        if ($permissions === null) {
            $subscription = $request->attributes->get('application_subscription');
            $permissions = $this->access->navigationPermissions($request->user('web'), $subscription?->plan);
            $request->attributes->set('navigation_permissions', $permissions);
        }

        $routeName = $request->route()->getName();
        $requiredPermissions = array_merge([
            $modulePermission,
            config('permissions.tenant_routes')[$routeName] ?? '',
        ], config('permissions.tenant_route_requirements', [])[$routeName] ?? []);
        $alternativePermissions = config('permissions.tenant_route_alternatives', [])[$routeName] ?? [];
        $grantedPermissions = array_keys($permissions);

        $missingPermissions = array_diff($requiredPermissions, $grantedPermissions);
        $hasAlternative = $alternativePermissions === []
            || array_intersect($alternativePermissions, $grantedPermissions) !== [];

        abort_unless($missingPermissions === [] && $hasAlternative, 403, 'Your account or plan does not include this action.');

        return $next($request);
    }
}
