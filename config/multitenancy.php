<?php

use Modules\Core\Models\Tenant;
use Modules\Core\Tasks\SwitchTenantSettings;
use Spatie\Multitenancy\Tasks\SwitchTenantDatabaseTask;

return [
    // Resolve domains in middleware so boot never queries the tenant registry.
    'tenant_finder' => null,

    'tenant_model' => Tenant::class,
    'tenant_database_connection_name' => 'tenant',
    'landlord_database_connection_name' => 'central',
    'tenant_artisan_search_fields' => ['id', 'slug'],
    'queues_are_tenant_aware_by_default' => true,

    'switch_tenant_tasks' => [
        SwitchTenantDatabaseTask::class,
        SwitchTenantSettings::class,
    ],

    'central_domains' => array_values(array_filter(array_map(
        static fn (string $domain): string => strtolower(rtrim(trim($domain), '.')),
        explode(',', env('CENTRAL_DOMAINS', 'platform.localhost')),
    ))),

    'database_prefix' => env('TENANT_DATABASE_PREFIX', 'tenant_'),

    'migrations' => [
        'central' => [
            database_path('migrations'),
            base_path('Modules/Core/database/migrations/shared'),
            base_path('Modules/Core/database/migrations/central'),
        ],
        'tenant' => array_values(array_filter([
            base_path('Modules/Core/database/migrations/shared'),
            base_path('Modules/TenantCore/database/migrations'),
        ], 'is_dir')),
    ],
];
