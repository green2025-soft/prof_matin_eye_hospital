<?php

use Modules\Accounting\Database\Seeders\AccountingDatabaseSeeder;
use Modules\Core\Models\Tenant;
use Modules\Core\Tasks\SwitchTenantDatabase;
use Modules\Core\Tasks\SwitchTenantSettings;

$centralDomains = array_values(array_filter(array_map(
    static fn (string $domain): string => strtolower(rtrim(trim($domain), '.')),
    explode(',', env('CENTRAL_DOMAINS', 'localhost')),
)));
$centralHost = $centralDomains[0] ?? 'localhost';
$parentDomain = explode('.', $centralHost, 2)[1] ?? $centralHost;

return [
    // Resolve domains in middleware so boot never queries the tenant registry.
    'tenant_finder' => null,

    'tenant_model' => Tenant::class,
    'tenant_database_connection_name' => 'tenant',
    'landlord_database_connection_name' => 'central',
    'tenant_artisan_search_fields' => ['id', 'slug'],
    'queues_are_tenant_aware_by_default' => true,

    'switch_tenant_tasks' => [
        SwitchTenantDatabase::class,
        SwitchTenantSettings::class,
    ],

    'central_domains' => $centralDomains,
    'tenant_base_domain' => strtolower(rtrim(trim(env('TENANT_BASE_DOMAIN') ?: $parentDomain), '.')),

    'migrations' => [
        'central' => [
            database_path('migrations'),
            base_path('Modules/Core/database/migrations/shared'),
            base_path('Modules/Core/database/migrations/central'),
        ],
        'tenant' => array_values(array_filter([
            base_path('Modules/Core/database/migrations/shared'),
            base_path('Modules/Core/database/migrations/tenant'),
            base_path('Modules/TenantCore/database/migrations'),
            base_path('Modules/Accounting/database/migrations'),
            base_path('Modules/Hospital/database/migrations'),
            base_path('Modules/Pharmacy/database/migrations'),
            base_path('Modules/Prescription/database/migrations'),
        ], 'is_dir')),
    ],
    'tenant_seeders' => [
        'Accounting' => AccountingDatabaseSeeder::class,
    ],
];
