<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Core\Models\Subscription;
use Modules\Core\Models\Tenant;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveSubscription
{
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = Tenant::current();

        if ($tenant === null) {
            return $next($request);
        }

        $subscription = Subscription::forTenant($tenant);
        $request->attributes->set('application_subscription', $subscription);

        if (! $subscription?->isUsable()) {
            abort_if($request->expectsJson(), 403, 'An active subscription is required.');

            return redirect()->route('tenantcore.billing.index')->with('warning', 'Your subscription needs attention. Review Billing to restore workspace access.');
        }

        $permission = config('permissions.tenant_routes')[$request->route()->getName()] ?? '';
        abort_unless($subscription->plan->allowsRoutePermission($permission), 403, 'Your plan does not include this action.');

        return $next($request);
    }
}
