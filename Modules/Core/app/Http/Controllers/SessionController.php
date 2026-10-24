<?php

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Modules\Core\Contracts\TenantAccounts;
use Modules\Core\Http\Requests\LoginRequest;
use Modules\Core\Models\Account;
use Modules\Core\Models\Admin;
use Modules\Core\Models\Tenant;
use Modules\Core\Services\AccessManager;

class SessionController extends Controller
{
    public function create(Request $request): View
    {
        $central = $request->attributes->get('application_scope') === 'central';

        return view('core::auth.login', [
            'loginTitle' => $central ? 'Sign in to Core administration' : 'Sign in to your workspace',
            'loginDescription' => $central
                ? 'Manage tenants, plans, subscriptions, invoices and payments.'
                : 'Manage your team, permissions, billing and workspace settings.',
        ]);
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        return redirect()->intended(route('home'));
    }

    public function enterTenant(Request $request, #[\SensitiveParameter] string $token, TenantAccounts $accounts, AccessManager $access): RedirectResponse
    {
        $tenant = Tenant::current();
        abort_unless($tenant !== null && preg_match('/\A[A-Za-z0-9]{64}\z/', $token), 403);
        $key = 'tenant-entry:'.hash('sha256', $token);
        $cache = Cache::store('tenant-handoff');

        $cache->lock($key.':lock', 10)->block(3, function () use ($request, $tenant, $key, $cache, $access): void {
            $handoff = $cache->pull($key);
            abort_unless(is_array($handoff)
                && ($handoff['tenant_id'] ?? null) === $tenant->getKey()
                && ($handoff['domain'] ?? null) === $request->getHost()
                && ($handoff['expires_at'] ?? 0) > now()->getTimestamp(), 403);

            try {
                Tenant::forgetCurrent();
                $admin = Admin::query()->findOrFail($handoff['admin_id']);
                $access->transaction($admin, function (Account $actor) use ($handoff, $request): void {
                    $current = Tenant::query()->whereKey($handoff['tenant_id'])->lockForUpdate()->firstOrFail();
                    Gate::forUser($actor)->authorize('enter', $current);
                    abort_unless($current->domains()->where('domain', $request->getHost())->exists(), 403);
                });
            } finally {
                $tenant->makeCurrent();
            }
        });

        $owner = $accounts->ownerForLogin();
        $guard = Auth::guard('web');
        $guard->logoutCurrentDevice();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->session()->put('application_scope', $request->attributes->get('application_scope'));
        $request->session()->put('ensure_valid_tenant_session_tenant_id', $tenant->getKey());
        $guard->login($owner, false);

        return redirect()->route('home')->with('status', 'Tenant workspace opened.')
            ->withHeaders(['Cache-Control' => 'no-store', 'Referrer-Policy' => 'no-referrer']);
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.login');
    }
}
