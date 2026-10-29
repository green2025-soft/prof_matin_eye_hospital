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
        Schema::connection('tenant')->create('doctors', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->restrictOnDelete();
            $table->string('name');
            $table->string('bn_name')->nullable();
            $table->string('mobile_no', 30);
            $table->string('designation')->nullable();
            $table->foreignId('department_id')->constrained('departments')->restrictOnDelete();
            $table->string('specialist')->nullable();
            $table->string('bn_specialist')->nullable();
            $table->string('bmdc_reg', 100)->nullable();
            $table->string('bn_bmdc_reg', 100)->nullable();
            $table->string('gender', 10);
            $table->string('blood_group', 3)->nullable();
            $table->text('address')->nullable();
            $table->text('education_degree')->nullable();
            $table->text('bn_education_degree')->nullable();
            $table->text('current_employment')->nullable();
            $table->text('bn_current_employment')->nullable();
            $table->decimal('consultation_fee', 20, 2)->default(0);
            $table->decimal('follow_up_fee', 20, 2)->default(0);
            $table->text('pad_footer_text')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('picture_path')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->index(['branch_id', 'name', 'id']);
        });

        Schema::connection('tenant')->create('doctor_password_reset_tokens', function (Blueprint $table): void {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        DB::connection('tenant')->table('roles')->insertOrIgnore([
            'name' => 'dr',
            'guard_name' => 'web',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('doctor_password_reset_tokens');
        Schema::connection('tenant')->dropIfExists('doctors');
        // The dr role may have acquired assignments or grants after deployment.
    }
};
