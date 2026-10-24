<?php

use Illuminate\Contracts\Auth\Middleware\AuthenticatesRequests;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Modules\Core\Http\Middleware\EnsureCentralApplication;
use Modules\Core\Http\Middleware\EnsureSessionScope;
use Modules\Core\Http\Middleware\ResolveApplication;
use Spatie\Multitenancy\Exceptions\NoCurrentTenant;
use Spatie\Multitenancy\Http\Middleware\NeedsTenant;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(fn (): string => route('auth.login'));
        $middleware->redirectUsersTo(fn (): string => route('home'));

        $middleware->web(prepend: [ResolveApplication::class], append: [EnsureSessionScope::class, AuthenticateSession::class]);
        $middleware->prependToPriorityList(EncryptCookies::class, ResolveApplication::class);
        $middleware->appendToPriorityList(StartSession::class, EnsureSessionScope::class);
        $middleware->prependToPriorityList(AuthenticatesRequests::class, EnsureCentralApplication::class);
        $middleware->prependToPriorityList(AuthenticatesRequests::class, NeedsTenant::class);

        $middleware->alias([
            'central' => EnsureCentralApplication::class,
            'tenant' => NeedsTenant::class,
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->map(NoCurrentTenant::class, fn (NoCurrentTenant $exception): NotFoundHttpException => new NotFoundHttpException(previous: $exception));
        $exceptions->dontFlash(['remember_token']);
    })->create();
