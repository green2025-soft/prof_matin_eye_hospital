<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Core\Enums\AccessPermission;
use Modules\Core\Enums\PlatformPermission;
use Modules\Core\Models\Account;
use Modules\Core\Models\Admin;
use Modules\Core\Models\SmsAccount;
use Modules\Core\Models\Subscription;
use Modules\Core\Models\Tenant;
use Modules\Core\Services\PermissionCatalog;
use Symfony\Component\HttpFoundation\Response;

class EnsureModulePermission
{
    public function handle(Request $request, Closure $next, string $modulePermission = ''): Response
    {
        $permissions = $request->attributes->get('navigation_permissions');

        // The route and its page controls reuse the same account-and-plan permissions.
        if ($permissions === null) {
            $actor = $request->user('web');
            $actor->assertApplicationContext();
            $actor->loadMissing($actor->authenticationRelations());
            $permissions = $this->permissions($request, $actor);
            $request->attributes->set('navigation_permissions', $permissions);
        }

        $routeName = $request->route()->getName();
        $routes = array_replace(config('permissions.tenant_routes', []), config('permissions.platform_routes', []));
        $requiredPermissions = array_filter(array_merge([
            $modulePermission,
            $routes[$routeName] ?? '',
        ], config('permissions.tenant_route_requirements', [])[$routeName] ?? []));
        $alternativePermissions = config('permissions.tenant_route_alternatives', [])[$routeName] ?? [];
        abort_unless(
            isset($routes[$routeName]) || $alternativePermissions !== []
                || in_array($routeName, config('permissions.authenticated_routes', []), true),
            403,
            'This action has no permission configured.',
        );
        $grantedPermissions = array_keys($permissions);

        $missingPermissions = array_diff($requiredPermissions, $grantedPermissions);
        $hasAlternative = $alternativePermissions === []
            || array_intersect($alternativePermissions, $grantedPermissions) !== [];

        abort_unless($missingPermissions === [] && $hasAlternative, 403, 'Your account or plan does not include this action.');

        return $next($request);
    }

    private function permissions(Request $request, Account $actor): array
    {
        $central = $actor instanceof Admin;
        $tenant = Tenant::current();
        $subscription = $request->attributes->has('application_subscription')
            ? $request->attributes->get('application_subscription')
            : ($tenant === null ? null : Subscription::forTenant($tenant));
        $request->attributes->set('application_subscription', $subscription);
        $plan = $subscription?->isUsable() ? $subscription->plan : null;

        $owner = $actor->isOwner();

        if ($owner) {
            $names = array_merge(
                array_column(AccessPermission::cases(), 'value'),
                $central ? array_column(PlatformPermission::cases(), 'value') : PermissionCatalog::tenantPermissions(),
            );
        } else {
            $names = $actor->permissionNames()->all();
        }

        $request->attributes->set('account_access_snapshot', [
            'actor' => $actor,
            'owner' => $owner,
            'permissions' => $names,
        ]);

        $permissions = [];

        foreach (array_unique($names) as $name) {
            if ($central || str_starts_with($name, 'billing.')
                || in_array($name, ['sms.view', 'sms-recharges.create'], true)
                || $plan?->allowsRoutePermission($name)) {
                $permissions[$name] = true;
            }
        }

        if (! $central && SmsAccount::availableFeatures() === []) {
            unset($permissions['sms.view'], $permissions['sms-recharges.create']);
        }

        return $permissions;
    }
}
