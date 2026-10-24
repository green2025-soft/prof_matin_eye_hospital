<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Modules\Core\Http\Requests\TenantRequest;
use Modules\Core\Models\Tenant;
use Modules\Core\Services\TenantProvisioner;

class TenantController extends BaseController
{
    protected string $model = Tenant::class;

    protected string $title = 'Tenant';

    protected string $service = TenantProvisioner::class;

    protected string $views = 'core::tenants';

    protected string $parameter = 'tenant';

    public string $routePrefix = 'core.tenants';

    public function store(TenantRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }
}
