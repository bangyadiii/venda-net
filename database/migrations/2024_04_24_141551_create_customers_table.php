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
            $table->id();
            $table->string('secret_id')->unique();
            $table->string('customer_name')->nullable();
            $table->string('phone_number')->nullable();
            $table->datetime('active_date')->nullable();
            $table->datetime('invoice_date')->nullable();
            $table->string('ppp_username');
            $table->string('ppp_password');
            $table->foreignId('plan_id')
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
