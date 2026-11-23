<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const TABLES = [
        'phr_purchase_payment_allocations' => 'phr_purchase_payment_items',
        'phr_sale_payment_allocations' => 'phr_sale_payment_items',
    ];

    public function up(): void
    {
        $this->renameTables(self::TABLES);
    }

    public function down(): void
    {
        $this->renameTables(array_flip(self::TABLES));
    }

    private function renameTables(array $tables): void
    {
        $schema = Schema::connection('tenant');

        foreach ($tables as $from => $to) {
            if ($schema->hasTable($from) === $schema->hasTable($to)) {
                throw new RuntimeException("Expected exactly one Pharmacy payment table to exist: {$from} or {$to}.");
            }
        }

        foreach ($tables as $from => $to) {
            if ($schema->hasTable($from)) {
                $schema->rename($from, $to);
            }
        }
    }
};
