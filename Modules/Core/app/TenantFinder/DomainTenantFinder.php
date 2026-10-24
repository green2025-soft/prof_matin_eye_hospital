<?php

namespace Modules\Core\TenantFinder;

use Illuminate\Http\Request;
use Modules\Core\Models\Domain;
use Modules\Core\Models\Tenant;
use Spatie\Multitenancy\Contracts\IsTenant;
use Spatie\Multitenancy\TenantFinder\TenantFinder;

class DomainTenantFinder extends TenantFinder
{
    public function findForRequest(Request $request): ?IsTenant
    {
        $host = $this->normalizeHost($request->getHost());
        $centralDomains = array_map($this->normalizeHost(...), config('multitenancy.central_domains', []));

        if (in_array($host, $centralDomains, true)) {
            return null;
        }

        $domain = Domain::query()->with('tenant')->where('domain', $host)->first();
        $tenant = $domain?->tenant;

        abort_unless($tenant && $tenant->status === Tenant::STATUS_ACTIVE, 404);

        return $tenant;
    }

    private function normalizeHost(string $host): string
    {
        return strtolower(rtrim($host, '.'));
    }
}
