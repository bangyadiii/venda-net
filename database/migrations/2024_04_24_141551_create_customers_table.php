<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('customer_name')->nullable();
            $table->string('phone_number')
                ->nullable()
                ->unique();
            $table->text('address')->nullable();

            $table->enum('installment_status', ['not_installed', 'installed'])
                ->default('not_installed');
            
            $table->enum('service_status', ['active', 'inactive', 'suspended'])
                ->default('active');

            $table->date('active_date')->nullable();
            $table->boolean('auto_isolir')->default(true);
            $table->tinyInteger('isolir_date')->nullable();
            $table->string('secret_id')->nullable();

            $table->foreignId('plan_id')
                ->nullable()
                ->constrained('plans')
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
