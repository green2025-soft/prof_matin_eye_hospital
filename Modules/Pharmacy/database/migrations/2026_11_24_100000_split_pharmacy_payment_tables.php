<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'tenant';

    private const SALE_PAYMENT_COLUMNS = [
        'id', 'branch_id', 'number', 'product_type', 'payment_date', 'customer_id',
        'payment_type_id', 'amount', 'discount', 'notes', 'voided_at', 'created_at', 'updated_at',
    ];

    private const PURCHASE_PAYMENT_COLUMNS = [
        'id', 'branch_id', 'number', 'product_type', 'payment_date', 'supplier_id',
        'payment_type_id', 'amount', 'discount', 'notes', 'voided_at', 'created_at', 'updated_at',
    ];

    public function up(): void
    {
        $schema = Schema::connection('tenant');
        $connection = DB::connection('tenant');

        foreach (['phr_payments', 'phr_sale_payment_items', 'phr_purchase_payment_items'] as $table) {
            if (! $schema->hasTable($table)) {
                throw new RuntimeException("Cannot split Pharmacy payments; required table {$table} is missing.");
            }
        }
        foreach (['phr_sale_phr_payments', 'phr_purchase_phr_payments'] as $table) {
            if ($schema->hasTable($table)) {
                throw new RuntimeException("Cannot split Pharmacy payments; target table {$table} already exists.");
            }
        }

        $payments = $connection->table('phr_payments');
        if ($payments->whereNotIn('direction', ['customer', 'supplier'])->exists()) {
            throw new RuntimeException('Cannot split Pharmacy payments; an unsupported payment direction exists.');
        }

        $this->assertPaymentForeignKey('phr_sale_payment_items');
        $this->assertPaymentForeignKey('phr_purchase_payment_items');
        $this->assertAllocationDirections($connection);
        $this->assertJournalSources($connection, $schema);

        $this->createSalePayments($schema);
        $this->createPurchasePayments($schema);

        $connection->table('phr_sale_phr_payments')->insertUsing(
            self::SALE_PAYMENT_COLUMNS,
            $connection->table('phr_payments')
                ->select(self::SALE_PAYMENT_COLUMNS)
                ->where('direction', 'customer'),
        );
        $connection->table('phr_purchase_phr_payments')->insertUsing(
            self::PURCHASE_PAYMENT_COLUMNS,
            $connection->table('phr_payments')
                ->select(self::PURCHASE_PAYMENT_COLUMNS)
                ->where('direction', 'supplier'),
        );

        $this->updateJournalSources($connection, $schema);
        $this->replacePaymentForeignKey('phr_sale_payment_items', 'phr_sale_phr_payments', 'phr_sale_payment_items_payment_id_foreign');
        $this->replacePaymentForeignKey('phr_purchase_payment_items', 'phr_purchase_phr_payments', 'phr_purchase_payment_items_payment_id_foreign');

        $schema->drop('phr_payments');
    }

    public function down(): void
    {
        throw new RuntimeException('The Pharmacy payment split is forward-only. Restore a backup or use a new forward migration.');
    }

    private function createSalePayments($schema): void
    {
        $schema->create('phr_sale_phr_payments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->restrictOnDelete();
            $table->string('number', 40)->unique();
            $table->string('product_type', 10);
            $table->date('payment_date');
            $table->foreignId('customer_id')->nullable()->constrained('patients')->restrictOnDelete();
            $table->foreignId('payment_type_id')->nullable()->constrained('payment_types')->restrictOnDelete();
            $table->unsignedBigInteger('amount');
            $table->unsignedBigInteger('discount')->default(0);
            $table->text('notes')->nullable();
            $table->timestamp('voided_at')->nullable();
            $table->timestamps();
            $table->index(['branch_id', 'payment_date', 'id']);
            $table->index(['branch_id', 'customer_id', 'id']);
        });
    }

    private function createPurchasePayments($schema): void
    {
        $schema->create('phr_purchase_phr_payments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->restrictOnDelete();
            $table->string('number', 40)->unique();
            $table->string('product_type', 10);
            $table->date('payment_date');
            $table->foreignId('supplier_id')->nullable()->constrained('phr_suppliers')->restrictOnDelete();
            $table->foreignId('payment_type_id')->nullable()->constrained('payment_types')->restrictOnDelete();
            $table->unsignedBigInteger('amount');
            $table->unsignedBigInteger('discount')->default(0);
            $table->text('notes')->nullable();
            $table->timestamp('voided_at')->nullable();
            $table->timestamps();
            $table->index(['branch_id', 'payment_date', 'id']);
            $table->index(['branch_id', 'supplier_id', 'id']);
        });
    }

    private function assertJournalSources($connection, $schema): void
    {
        if (! $schema->hasTable('acc_journal_entries')) {
            return;
        }

        $oldSource = 'Modules\\Pharmacy\\Models\\Payment';
        $payments = $connection->table('phr_payments')->select('id');
        $orphanJournals = $connection->table('acc_journal_entries')
            ->where('source_type', $oldSource)
            ->whereNotIn('source_id', $payments);
        if ((clone $orphanJournals)->whereNull('voided_at')->exists()) {
            throw new RuntimeException('Cannot split Pharmacy payments; an active Accounting journal references a missing payment.');
        }
    }

    private function assertAllocationDirections($connection): void
    {
        $payments = $connection->table('phr_payments');
        $salePaymentIds = (clone $payments)->select('id')->where('direction', 'customer');
        $purchasePaymentIds = (clone $payments)->select('id')->where('direction', 'supplier');
        if ($connection->table('phr_sale_payment_items')->whereNotIn('payment_id', $salePaymentIds)->exists()
            || $connection->table('phr_purchase_payment_items')->whereNotIn('payment_id', $purchasePaymentIds)->exists()) {
            throw new RuntimeException('Cannot split Pharmacy payments; an allocation is linked to the wrong payment type.');
        }
    }

    private function updateJournalSources($connection, $schema): void
    {
        if (! $schema->hasTable('acc_journal_entries')) {
            return;
        }

        $oldSource = 'Modules\\Pharmacy\\Models\\Payment';
        $payments = $connection->table('phr_payments');
        $connection->table('acc_journal_entries')
            ->where('source_type', $oldSource)
            ->whereIn('source_id', (clone $payments)->select('id')->where('direction', 'customer'))
            ->update(['source_type' => 'Modules\\Pharmacy\\Models\\SalePayment']);
        $connection->table('acc_journal_entries')
            ->where('source_type', $oldSource)
            ->whereIn('source_id', (clone $payments)->select('id')->where('direction', 'supplier'))
            ->update(['source_type' => 'Modules\\Pharmacy\\Models\\PurchasePayment']);

        // Keep reversed journals after hard deletion, but detach their stale source morph.
        $connection->table('acc_journal_entries')
            ->where('source_type', $oldSource)
            ->whereNotIn('source_id', (clone $payments)->select('id'))
            ->update([
                'source_type' => null,
                'source_id' => null,
            ]);
    }

    private function replacePaymentForeignKey(string $tableName, string $targetTable, string $newName): void
    {
        $schema = Schema::connection('tenant');
        $paymentForeignKey = $this->paymentForeignKey($tableName);

        $schema->table($tableName, function (Blueprint $table) use ($paymentForeignKey): void {
            $table->dropForeign($paymentForeignKey['name']);
        });
        $schema->table($tableName, function (Blueprint $table) use ($targetTable, $newName): void {
            $table->foreign('payment_id', $newName)
                ->references('id')
                ->on($targetTable)
                ->restrictOnDelete();
        });
    }

    private function assertPaymentForeignKey(string $tableName): void
    {
        $this->paymentForeignKey($tableName);
    }

    private function paymentForeignKey(string $tableName): array
    {
        $paymentForeignKeys = array_values(array_filter(
            Schema::connection('tenant')->getForeignKeys($tableName),
            fn (array $foreignKey): bool => $foreignKey['columns'] === ['payment_id']
                && $foreignKey['foreign_table'] === 'phr_payments',
        ));
        if (count($paymentForeignKeys) !== 1 || $paymentForeignKeys[0]['name'] === null) {
            throw new RuntimeException("Cannot split Pharmacy payments; expected one payment foreign key on {$tableName}.");
        }

        return $paymentForeignKeys[0];
    }
};
