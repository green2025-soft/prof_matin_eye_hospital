<?php

namespace Modules\Core\TenantFinder;

use Illuminate\Http\Request;
use Modules\Core\Models\Tenant;
use Modules\Core\Services\ContextCache;
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

        $model = new Tenant;
        $attributes = app(ContextCache::class)->remember($model->getConnectionName(), 'tenant-domains', $host, function () use ($host): array {
            $tenant = Tenant::query()->where('status', Tenant::STATUS_ACTIVE)
                ->whereRelation('domains', 'domain', $host)->first();

            abort_unless($tenant, 404);

            return $tenant->getAttributes();
        });

        return $model->newFromBuilder($attributes);
    }

    private function normalizeHost(string $host): string
    {
        return strtolower(rtrim($host, '.'));
    }
}
