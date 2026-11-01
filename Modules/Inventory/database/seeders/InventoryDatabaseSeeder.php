<?php

namespace Modules\Inventory\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Core\Models\Tenant;
use RuntimeException;

class InventoryDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (! Tenant::checkCurrent()) {
            throw new RuntimeException('Inventory catalog seeding requires a current tenant.');
        }

        $directory = module_path('Inventory', 'database/data');
        $manifest = json_decode(file_get_contents($directory.'/manifest.json'), true, flags: JSON_THROW_ON_ERROR);
        $path = $directory.'/'.$manifest['file'];
        $checksum = hash_file('sha256', $path);

        if (! hash_equals($manifest['sha256'], $checksum)) {
            throw new RuntimeException('The Inventory catalog snapshot does not match its manifest.');
        }

        $snapshot = json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
        $connection = DB::connection('tenant');
        $lockName = hash('sha256', 'inventory-catalog:'.$connection->getDatabaseName());
        $lock = $connection->selectOne('SELECT GET_LOCK(?, 30) AS acquired', [$lockName], false);

        if ((int) ($lock?->acquired) !== 1) {
            throw new RuntimeException('The Inventory catalog is being initialized. Retry tenant setup.');
        }

        try {
            $connection->transaction(function () use ($connection, $manifest, $snapshot, $checksum): void {
                // A completed run never overwrites tenant-maintained catalog data.
                if ($connection->table('inventory_seed_runs')->exists()) {
                    return;
                }

                $now = now();
                $tables = [
                    'categories' => 'inventory_product_categories',
                    'dosages' => 'inventory_dosages',
                    'suppliers' => 'inventory_suppliers',
                    'shelves' => 'inventory_shelves',
                    'units' => 'inventory_units',
                ];
                $ids = [];

                foreach ($tables as $key => $table) {
                    $rows = array_map(fn (array $row): array => $row + ['created_at' => $now, 'updated_at' => $now], $snapshot[$key]);

                    foreach (array_chunk($rows, 250) as $chunk) {
                        if ($key === 'units') {
                            $connection->table($table)->insertOrIgnore($chunk);
                        } else {
                            $connection->table($table)->insert($chunk);
                        }
                    }

                    $ids[$key] = $connection->table($table)->whereNotNull('source_key')->pluck('id', 'source_key')->all();
                    if ($key === 'units') {
                        $unitsBySymbol = $connection->table($table)->whereIn('symbol', array_column($snapshot['units'], 'symbol'))
                            ->get(['id', 'symbol'])->keyBy(fn (object $unit): string => mb_strtolower(trim($unit->symbol)));
                        foreach ($snapshot['units'] as $sourceUnit) {
                            $unit = $unitsBySymbol->get(mb_strtolower(trim($sourceUnit['symbol'])));
                            if ($unit === null) {
                                throw new RuntimeException('The Inventory catalog base unit is unavailable.');
                            }
                            $ids['units'][$sourceUnit['source_key']] = $unit->id;
                        }
                    }
                }

                foreach (array_chunk($snapshot['products'], 200) as $chunk) {
                    $rows = [];

                    foreach ($chunk as $product) {
                        $rows[] = [
                            'source_key' => $product['source_key'],
                            'type' => $product['type'],
                            'name' => $product['name'],
                            'code' => $product['code'],
                            'category_id' => $ids['categories'][$product['category_key']] ?? null,
                            'dosage_id' => $ids['dosages'][$product['dosage_key']] ?? null,
                            'supplier_id' => $ids['suppliers'][$product['supplier_key']] ?? null,
                            'shelf_id' => $ids['shelves'][$product['shelf_key']] ?? null,
                            'strength' => $product['strength'],
                            'purchase_price' => $product['purchase_price'],
                            'sale_price' => $product['sale_price'],
                            'reorder_level' => $product['reorder_level'],
                            'needs_setup' => $product['needs_setup'],
                            'active' => true,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }

                    $connection->table('inventory_products')->insert($rows);
                    $productIds = $connection->table('inventory_products')->whereIn('source_key', array_column($chunk, 'source_key'))->pluck('id', 'source_key');
                    $variants = [];
                    $units = [];

                    foreach ($chunk as $product) {
                        $productId = $productIds[$product['source_key']];
                        $variants[] = [
                            'product_id' => $productId,
                            'sku' => $product['sku'],
                            'name' => 'Default',
                            'source_key' => $product['source_key'].':default',
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                        $units[] = [
                            'product_id' => $productId,
                            'unit_id' => $ids['units']['base:pcs'],
                            'factor' => 1,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }

                    $connection->table('inventory_product_variants')->insert($variants);
                    $connection->table('inventory_product_units')->insert($units);
                }

                $connection->table('inventory_seed_runs')->insert([
                    'version' => $manifest['version'],
                    'checksum' => $checksum,
                    'counts' => json_encode($manifest['counts'], JSON_THROW_ON_ERROR),
                    'completed_at' => $now,
                ]);
            }, 3);
        } finally {
            $connection->selectOne('SELECT RELEASE_LOCK(?) AS released', [$lockName], false);
        }
    }
}
