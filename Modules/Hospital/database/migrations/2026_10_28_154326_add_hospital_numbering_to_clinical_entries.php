<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('tenant')->create('clinical_sequences', function (Blueprint $table): void {
            $table->string('scope', 120)->primary();
            $table->unsignedBigInteger('last_number')->default(0);
        });

        foreach (['appointments' => 'appointment_date', 'consultations' => 'entry_date'] as $tableName => $dateColumn) {
            Schema::connection('tenant')->table($tableName, function (Blueprint $table) use ($tableName, $dateColumn): void {
                $table->string('serial_no', 120)->nullable();
                $table->string('serial_scope', 120)->nullable();
                $table->string('invoice_no', 120)->nullable()->unique();
                $table->unique(['serial_scope', 'serial_no'], $tableName.'_serial_scope_no_unique');
                $table->unique(['branch_id', 'doctor_id', $dateColumn, 'serial_no'], $tableName.'_queue_serial_unique');
            });
        }
        foreach (['patient_investigations', 'patient_operations'] as $tableName) {
            Schema::connection('tenant')->table($tableName, function (Blueprint $table): void {
                $table->string('invoice_no', 120)->nullable()->unique();
            });
        }

        $this->moveSettings('patients', 'hospital', [
            'id_scope' => 'tenant',
            'appointment_serial_scope' => 'doctor',
            'appointment_serial_reset' => 'day',
            'appointment_serial_pattern' => '{SEQ:3}',
            'appointment_invoice_enabled' => false,
            'appointment_invoice_scope' => 'branch',
            'appointment_invoice_reset' => 'never',
            'appointment_invoice_pattern' => 'APT-B{BRANCH}-{SEQ:6}',
            'consultation_serial_enabled' => true,
            'consultation_serial_scope' => 'doctor',
            'consultation_serial_reset' => 'day',
            'consultation_serial_pattern' => '{SEQ:3}',
            'consultation_invoice_enabled' => true,
            'consultation_invoice_scope' => 'branch',
            'consultation_invoice_reset' => 'never',
            'consultation_invoice_pattern' => 'CON-B{BRANCH}-{SEQ:6}',
            'investigation_invoice_scope' => 'branch',
            'investigation_invoice_reset' => 'never',
            'investigation_invoice_pattern' => 'INV-B{BRANCH}-{SEQ:6}',
            'operation_invoice_scope' => 'branch',
            'operation_invoice_reset' => 'never',
            'operation_invoice_pattern' => 'OP-B{BRANCH}-{SEQ:6}',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback removes generated numbers/counters; merged settings JSON is retained.
        foreach (['appointments', 'consultations'] as $tableName) {
            Schema::connection('tenant')->table($tableName, function (Blueprint $table) use ($tableName): void {
                $table->dropUnique($tableName.'_queue_serial_unique');
                $table->dropUnique($tableName.'_serial_scope_no_unique');
                $table->dropUnique(['invoice_no']);
                $table->dropColumn(['serial_no', 'serial_scope', 'invoice_no']);
            });
        }
        foreach (['patient_investigations', 'patient_operations'] as $tableName) {
            Schema::connection('tenant')->table($tableName, function (Blueprint $table): void {
                $table->dropUnique(['invoice_no']);
                $table->dropColumn('invoice_no');
            });
        }
        Schema::connection('tenant')->dropIfExists('clinical_sequences');
        $this->moveSettings('hospital', 'patients', ['id_scope' => 'tenant']);
    }

    private function moveSettings(string $from, string $to, array $defaults): void
    {
        $connection = DB::connection('tenant');
        $connection->transaction(function () use ($connection, $from, $to, $defaults): void {
            $settings = $connection->table('settings')->whereIn('group', [$from, $to])
                ->orderBy('group')->lockForUpdate()->get()->keyBy('group');
            $source = $settings->get($from);
            $target = $settings->get($to);
            $sourceData = $source === null ? [] : json_decode($source->data, true, 512, JSON_THROW_ON_ERROR);
            $targetData = $target === null ? [] : json_decode($target->data, true, 512, JSON_THROW_ON_ERROR);
            $values = [
                'group' => $to,
                'data' => json_encode(array_replace($defaults, $sourceData, $targetData), JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR),
                'secrets' => $target->secrets ?? $source->secrets ?? null,
                'updated_at' => now(),
            ];

            if ($target !== null) {
                $connection->table('settings')->where('id', $target->id)->update($values);
                if ($source !== null) {
                    $connection->table('settings')->where('id', $source->id)->delete();
                }
            } elseif ($source !== null) {
                $connection->table('settings')->where('id', $source->id)->update($values);
            } else {
                $connection->table('settings')->insert($values + ['created_at' => now()]);
            }
        });
    }
};
