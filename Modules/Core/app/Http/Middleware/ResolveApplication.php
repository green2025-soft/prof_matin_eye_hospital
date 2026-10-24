<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthManager;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Cookie\CookieJar;
use Illuminate\Http\Request;
use Illuminate\Session\SessionManager;
use Modules\Core\Models\Tenant;
use Modules\Core\TenantFinder\DomainTenantFinder;
use Symfony\Component\HttpFoundation\Response;

class ResolveApplication
{
    public function __construct(
        private Application $app,
        private Repository $config,
        private DomainTenantFinder $finder,
        private AuthManager $auth,
        private SessionManager $sessions,
        private CookieJar $cookies,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $previous = $this->config->getMany(['auth.guards.web.provider', 'session.cookie', 'session.domain']);
        $request->attributes->set('application_previous_configuration', $previous);

        Tenant::forgetCurrent();
        $tenant = $this->finder->findForRequest($request);
        $tenant?->makeCurrent();

        $scope = $tenant ? 'tenant_'.$tenant->getKey() : 'central';
        $provider = $tenant ? 'users' : 'admins';
        $request->attributes->set('application_scope', $scope);
        $request->attributes->set('application_provider', $provider);
        $request->attributes->set('application_name', $tenant?->name ?? 'Platform');

        $this->config->set([
            'auth.guards.web.provider' => $provider,
            'session.cookie' => $previous['session.cookie'].'_'.$scope,
            'session.domain' => null,
        ]);
        $this->resetWebState();

        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        $previous = $request->attributes->get('application_previous_configuration');

        if (! is_array($previous)) {
            return;
        }

        $request->attributes->remove('application_previous_configuration');
        $this->config->set($previous);

        // Keep context through exception rendering, response collectors and streaming.
        try {
            $this->resetWebState();
        } finally {
            Tenant::forgetCurrent();
        }
    }

    private function resetWebState(): void
    {
        $this->auth->forgetGuards();
        $this->sessions->forgetDrivers();
        $this->cookies->flushQueuedCookies();
        $this->cookies->setDefaultPathAndDomain(
            $this->config->get('session.path'),
            $this->config->get('session.domain'),
            $this->config->get('session.secure'),
            $this->config->get('session.same_site'),
        );
        $this->app->forgetInstance('auth.driver');
        $this->app->forgetInstance('session.store');
    }
}
