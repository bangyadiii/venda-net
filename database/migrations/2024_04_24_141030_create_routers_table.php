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
        Schema::create('routers', function (Blueprint $table) {
            $table->id();
            $table->string('host');
            $table->string('username');
            $table->string('password');
            $table->datetime('last_connected_at')->nullable();

            $table->boolean('auto_isolir')->default(false);
            $table->string('isolir_action')->nullable();
            $table->string('isolir_profile_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('routers');
    }
};
