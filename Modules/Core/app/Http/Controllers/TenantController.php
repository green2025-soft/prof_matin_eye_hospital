<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Core\Http\Requests\TenantRequest;
use Modules\Core\Models\Account;
use Modules\Core\Models\Tenant;
use Modules\Core\Services\AccessManager;
use Modules\Core\Services\TenantProvisioner;

class TenantController extends BaseController
{
    protected string $model = Tenant::class;

    protected string $title = 'Tenant';

    protected string $service = TenantProvisioner::class;

    protected string $views = 'core::tenants';

    protected string $parameter = 'tenant';

    public function index(ListRequest $request): View
    {
        return $this->listData($request);
    }

    public function store(TenantRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }

    public function enter(Request $request, Tenant $tenant, AccessManager $access): RedirectResponse
    {
        return $access->transaction($request->user('web'), function (Account $actor) use ($request, $tenant): RedirectResponse {
            $current = Tenant::query()->whereKey($tenant->getKey())->lockForUpdate()->firstOrFail();
            Gate::forUser($actor)->authorize('enter', $current);
            $domain = $current->domains()->orderBy('id')->value('domain');
            abort_unless(is_string($domain) && $domain !== '', 404);

            $token = Str::random(64);
            $expires = now()->addMinute();
            $stored = Cache::store('tenant-handoff')->put('tenant-entry:'.hash('sha256', $token), [
                'admin_id' => $actor->getKey(),
                'tenant_id' => $current->getKey(),
                'domain' => $domain,
                'expires_at' => $expires->getTimestamp(),
            ], $expires);
            abort_unless($stored, 503, 'Tenant access could not be prepared. Please try again.');

            $path = URL::temporarySignedRoute('auth.tenant-entry', $expires, ['token' => $token], absolute: false);
            $scheme = app()->isProduction() ? 'https' : $request->getScheme();
            $port = in_array($request->getPort(), [80, 443], true) ? '' : ':'.$request->getPort();

            return redirect()->away($scheme.'://'.$domain.$port.$path)
                ->withHeaders(['Cache-Control' => 'no-store', 'Referrer-Policy' => 'no-referrer']);
        });
    }
}
