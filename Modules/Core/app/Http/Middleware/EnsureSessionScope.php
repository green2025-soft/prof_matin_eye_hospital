<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Core\Models\Tenant;
use Spatie\Multitenancy\Http\Middleware\EnsureValidTenantSession;
use Symfony\Component\HttpFoundation\Response;

class EnsureSessionScope
{
    public function __construct(private EnsureValidTenantSession $tenantSession) {}

    public function handle(Request $request, Closure $next): Response
    {
        $session = $request->session();
        $scope = $request->attributes->get('application_scope');

        abort_unless(is_string($scope) && $scope !== '', Response::HTTP_UNAUTHORIZED);

        if ($session->has('application_scope') && $session->get('application_scope') !== $scope) {
            abort(Response::HTTP_UNAUTHORIZED);
        }

        $session->put('application_scope', $scope);

        if (Tenant::checkCurrent()) {
            return $this->tenantSession->handle($request, $next);
        }

        return $next($request);
    }
}
