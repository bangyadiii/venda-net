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
        Schema::create('bills', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('customer_id');

            $table->integer('discount')->default(0);
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->integer('total_amount');
            $table->enum('status', ['paid', 'unpaid'])->default('unpaid');
            $table->date('due_date');
            $table->timestamps();

            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->cascadeOnDelete();

            $table->foreignId('plan_id')
                ->constrained('plans')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
