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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('game_id')->constrained();
            $table->foreignId('player_id')->constrained();
            $table->string('action'); // e.g., 'single', 'out', 'hr'
            $table->decimal('amount', 8, 2); // e.g., 1.00 or -1.00
            $table->integer('runs_scored')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
