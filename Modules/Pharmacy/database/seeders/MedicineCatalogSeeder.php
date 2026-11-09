<?php

namespace Modules\Pharmacy\Database\Seeders;

use Illuminate\Database\Connection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Modules\Core\Models\Tenant;
use Modules\Pharmacy\Models\Product;
use Modules\Pharmacy\Models\ProductCategory;
use Modules\Pharmacy\Models\ProductUnit;
use Modules\Pharmacy\Models\ProductVariant;
use RuntimeException;

class MedicineCatalogSeeder extends Seeder
{
    public function run(): void
    {
        if (! Tenant::checkCurrent()) {
            throw new RuntimeException('Medicine catalog seeding requires a current tenant.');
        }

        $directory = module_path('Pharmacy', 'database/data');
        $manifest = json_decode(file_get_contents($directory.'/manifest.json'), true, flags: JSON_THROW_ON_ERROR)['medicine'];
        $path = $directory.'/'.$manifest['file'];
        $checksum = hash_file('sha256', $path);

        if (! hash_equals($manifest['sha256'], $checksum)) {
            throw new RuntimeException('The Medicine catalog snapshot does not match its manifest.');
        }

        $snapshot = json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
        if ($snapshot['version'] !== $manifest['version'] || count($snapshot['products']) !== $manifest['count']) {
            throw new RuntimeException('The Medicine catalog snapshot has an unexpected version or product count.');
        }

        $connection = (new Product)->getConnection();
        $connection->transaction(function () use ($connection, $manifest, $snapshot, $checksum): void {
            // The identifier counter also serializes catalog imports for this tenant.
            Product::lockIdentifiers($connection);
            $completed = $connection->table('phr_seed_runs')
                ->where('version', $manifest['version'])
                ->first();

            if ($completed !== null) {
                if (! hash_equals($completed->checksum, $checksum)) {
                    throw new RuntimeException('A completed Medicine catalog version cannot be replaced.');
                }

                return;
            }

            $categoryIds = $this->categories($snapshot['categories']);
            $sourceKeys = array_merge(
                array_column($snapshot['products'], 'source_key'),
                array_filter(array_column($snapshot['products'], 'existing_source_key')),
            );
            $products = Product::query()
                ->where('type', 'medicine')
                ->whereIn('source_key', $sourceKeys)
                ->lockForUpdate()
                ->get()
                ->keyBy('source_key');
            $removed = $this->removeOldMedicine($products->modelKeys());
            $counts = ['created' => 0, 'updated' => 0, 'removed' => $removed];

            foreach ($snapshot['products'] as $row) {
                $result = $this->importProduct($row, $products, $categoryIds, $connection);
                $counts[$result]++;
            }

            if (Product::query()->where('type', 'medicine')->count() !== $manifest['count']) {
                throw new RuntimeException('The replacement Medicine catalog must contain exactly 325 products.');
            }

            $connection->table('phr_seed_runs')->insert([
                'version' => $manifest['version'],
                'checksum' => $checksum,
                'counts' => json_encode($counts, JSON_THROW_ON_ERROR),
                'completed_at' => now(),
            ]);
        }, 3);
    }

    private function categories(array $rows): array
    {
        $ids = [];

        foreach ($rows as $row) {
            $category = ProductCategory::query()
                ->where('source_key', $row['source_key'])
                ->first();
            $category ??= ProductCategory::query()
                ->firstOrNew(['type' => 'medicine', 'name' => $row['name']]);

            if (! $category->exists) {
                $category->forceFill(['source_key' => $row['source_key']]);
                $category->save();
            }

            $ids[$row['source_key']] = $category->id;
        }

        return $ids;
    }

    private function removeOldMedicine(array $retainedIds): int
    {
        $ids = Product::query()
            ->where('type', 'medicine')
            ->whereNotIn('id', $retainedIds)
            ->lockForUpdate()
            ->pluck('id');

        // Restrictive foreign keys keep referenced transaction and prescription data safe.
        ProductUnit::query()->whereIn('product_id', $ids)->delete();
        ProductVariant::query()->whereIn('product_id', $ids)->delete();

        return Product::query()->whereKey($ids)->delete();
    }

    private function importProduct(array $row, Collection $products, array $categoryIds, Connection $connection): string
    {
        $product = $products->get($row['source_key'])
            ?? $products->get($row['existing_source_key'])
            ?? new Product;
        $result = $product->exists ? 'updated' : 'created';
        $product->fill([
            'type' => 'medicine',
            'name' => $row['name'],
            'category_id' => $categoryIds[$row['category_key']],
            'dosage_id' => null,
            'supplier_id' => null,
            'shelf_id' => null,
            'strength' => null,
            'purchase_price' => '0.00',
            'sale_price' => $row['sale_price'],
            'reorder_level' => $row['reorder_level'],
            'active' => true,
            'needs_setup' => true,
        ]);

        if (! Product::identifierInUse($connection, $row['source_code'], $product->getKey())) {
            $product->barcode = $row['source_code'];
        }

        $product->forceFill(['source_key' => $row['source_key']]);
        $product->save();

        return $result;
    }
}
