<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Core\Models\Tenant;
use Modules\Core\Services\SmsService;
use Symfony\Component\HttpFoundation\Response;

class ProcessPendingSms
{
    public function __construct(private SmsService $sms) {}

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        $tenant = Tenant::current();

        if ($tenant !== null && ! $request->isMethodSafe() && $response->getStatusCode() < 400 && $request->user('web') !== null) {
            $this->sms->afterResponse($tenant);
        }

        return $response;
    }
}
