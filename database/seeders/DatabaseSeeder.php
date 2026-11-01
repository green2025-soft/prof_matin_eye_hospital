<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Core\Models\Tenant;
use Modules\Inventory\Database\Seeders\InventoryDatabaseSeeder;
use Nwidart\Modules\Facades\Module;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (Tenant::checkCurrent() && Module::isEnabled('Inventory')) {
            $this->call(InventoryDatabaseSeeder::class);
        }
    }
}
