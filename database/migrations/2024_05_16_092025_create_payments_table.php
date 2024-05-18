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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('bill_id');
            $table->decimal('amount', 10, 2);
            $table->datetime('payment_date');
            $table->string('method');
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->foreign('bill_id')
                ->references('id')
                ->on('bills')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
