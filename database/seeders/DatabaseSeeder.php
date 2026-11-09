<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Models\Tenant;
use Modules\Pharmacy\Database\Seeders\PharmacyDatabaseSeeder;
use Nwidart\Modules\Facades\Module;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (Tenant::checkCurrent() && Module::isEnabled('Pharmacy')) {
            $this->call(PharmacyDatabaseSeeder::class);
        }
    }
}
