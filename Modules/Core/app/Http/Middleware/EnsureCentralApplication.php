<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCentralApplication
{
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless($request->attributes->get('application_scope') === 'central', 404);

        return $next($request);
    }
}
