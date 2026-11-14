<?php

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\TenantCore\Models\Expense;
use Modules\TenantCore\Models\Income;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('income_items')) {
            Schema::create('income_items', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('income_id')->constrained()->cascadeOnDelete();
                $table->foreignId('head_id')->constrained('income_heads')->restrictOnDelete();
                $table->decimal('amount', 20, 2);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('expense_items')) {
            Schema::create('expense_items', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('expense_id')->constrained()->cascadeOnDelete();
                $table->foreignId('head_id')->constrained('expense_heads')->restrictOnDelete();
                $table->decimal('amount', 20, 2);
                $table->timestamps();
            });
        }

        // Preserve old voucher IDs, totals and journal links; only move the head into a child row.
        if (Schema::hasColumn('incomes', 'head_id')) {
            Model::withoutEvents(function (): void {
                Income::withoutGlobalScope('branch')
                    ->whereDoesntHave('items')
                    ->select(['id', 'head_id', 'amount', 'created_at', 'updated_at'])
                    ->chunkById(200, function (Collection $incomes): void {
                        foreach ($incomes as $income) {
                            $item = $income->items()->make([
                                'head_id' => $income->getRawOriginal('head_id'),
                                'amount' => $income->amount,
                            ]);
                            $item->created_at = $income->created_at;
                            $item->updated_at = $income->updated_at;
                            $item->save();
                        }
                    });
            });

            Schema::table('incomes', function (Blueprint $table): void {
                $table->dropConstrainedForeignId('head_id');
            });
        }

        if (Schema::hasColumn('expenses', 'head_id')) {
            Model::withoutEvents(function (): void {
                Expense::withoutGlobalScope('branch')
                    ->whereDoesntHave('items')
                    ->select(['id', 'head_id', 'amount', 'created_at', 'updated_at'])
                    ->chunkById(200, function (Collection $expenses): void {
                        foreach ($expenses as $expense) {
                            $item = $expense->items()->make([
                                'head_id' => $expense->getRawOriginal('head_id'),
                                'amount' => $expense->amount,
                            ]);
                            $item->created_at = $expense->created_at;
                            $item->updated_at = $expense->updated_at;
                            $item->save();
                        }
                    });
            });

            Schema::table('expenses', function (Blueprint $table): void {
                $table->dropConstrainedForeignId('head_id');
            });
        }
    }

    public function down(): void
    {
        throw new RuntimeException('Multiple voucher heads cannot be reduced to one without losing detail. Use a forward migration.');
    }
};
