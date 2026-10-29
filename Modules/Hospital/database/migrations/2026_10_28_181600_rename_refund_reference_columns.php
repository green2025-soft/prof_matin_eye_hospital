<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $connection = DB::connection('tenant');

        // MySQL requires separate foreign-key drop/add phases while checks remain enabled.
        $connection->statement(<<<'SQL'
            ALTER TABLE refunds
                DROP CHECK refunds_one_source_check,
                DROP FOREIGN KEY refunds_patient_investigation_id_branch_id_foreign,
                DROP FOREIGN KEY refunds_patient_operation_id_branch_id_foreign,
                DROP FOREIGN KEY refunds_refund_cause_id_foreign
            SQL);

        $connection->statement(<<<'SQL'
            ALTER TABLE refunds
                RENAME COLUMN patient_investigation_id TO investigation_id,
                RENAME COLUMN patient_operation_id TO operation_id,
                RENAME COLUMN refund_cause_id TO cause_id,
                RENAME INDEX refunds_patient_investigation_id_unique TO refunds_investigation_id_unique,
                RENAME INDEX refunds_patient_operation_id_unique TO refunds_operation_id_unique,
                RENAME INDEX refunds_patient_investigation_id_branch_id_foreign TO refunds_investigation_branch_foreign,
                RENAME INDEX refunds_patient_operation_id_branch_id_foreign TO refunds_operation_branch_foreign,
                RENAME INDEX refunds_refund_cause_id_foreign TO refunds_cause_id_foreign
            SQL);

        $connection->statement(<<<'SQL'
            ALTER TABLE refunds
                ADD CONSTRAINT refunds_investigation_branch_foreign FOREIGN KEY (investigation_id, branch_id)
                    REFERENCES patient_investigations (id, branch_id) ON UPDATE RESTRICT ON DELETE RESTRICT,
                ADD CONSTRAINT refunds_operation_branch_foreign FOREIGN KEY (operation_id, branch_id)
                    REFERENCES patient_operations (id, branch_id) ON UPDATE RESTRICT ON DELETE RESTRICT,
                ADD CONSTRAINT refunds_cause_id_foreign FOREIGN KEY (cause_id)
                    REFERENCES refund_causes (id) ON DELETE RESTRICT,
                ADD CONSTRAINT refunds_one_source_check
                    CHECK ((consultation_id IS NOT NULL) + (investigation_id IS NOT NULL) + (operation_id IS NOT NULL) = 1)
            SQL);
    }

    public function down(): void
    {
        $connection = DB::connection('tenant');

        $connection->statement(<<<'SQL'
            ALTER TABLE refunds
                DROP CHECK refunds_one_source_check,
                DROP FOREIGN KEY refunds_investigation_branch_foreign,
                DROP FOREIGN KEY refunds_operation_branch_foreign,
                DROP FOREIGN KEY refunds_cause_id_foreign
            SQL);

        $connection->statement(<<<'SQL'
            ALTER TABLE refunds
                RENAME COLUMN investigation_id TO patient_investigation_id,
                RENAME COLUMN operation_id TO patient_operation_id,
                RENAME COLUMN cause_id TO refund_cause_id,
                RENAME INDEX refunds_investigation_id_unique TO refunds_patient_investigation_id_unique,
                RENAME INDEX refunds_operation_id_unique TO refunds_patient_operation_id_unique,
                RENAME INDEX refunds_investigation_branch_foreign TO refunds_patient_investigation_id_branch_id_foreign,
                RENAME INDEX refunds_operation_branch_foreign TO refunds_patient_operation_id_branch_id_foreign,
                RENAME INDEX refunds_cause_id_foreign TO refunds_refund_cause_id_foreign
            SQL);

        $connection->statement(<<<'SQL'
            ALTER TABLE refunds
                ADD CONSTRAINT refunds_patient_investigation_id_branch_id_foreign FOREIGN KEY (patient_investigation_id, branch_id)
                    REFERENCES patient_investigations (id, branch_id) ON UPDATE RESTRICT ON DELETE RESTRICT,
                ADD CONSTRAINT refunds_patient_operation_id_branch_id_foreign FOREIGN KEY (patient_operation_id, branch_id)
                    REFERENCES patient_operations (id, branch_id) ON UPDATE RESTRICT ON DELETE RESTRICT,
                ADD CONSTRAINT refunds_refund_cause_id_foreign FOREIGN KEY (refund_cause_id)
                    REFERENCES refund_causes (id) ON DELETE RESTRICT,
                ADD CONSTRAINT refunds_one_source_check
                    CHECK ((consultation_id IS NOT NULL) + (patient_investigation_id IS NOT NULL) + (patient_operation_id IS NOT NULL) = 1)
            SQL);
    }
};
