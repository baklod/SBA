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
        Schema::create('playoff_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('division_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('bracket_side', ['upper', 'lower'])->default('upper');
            $table->integer('position')->default(1); // Slot position (1, 2, 3, etc.)
            $table->integer('round')->default(1); // Round number (1 = first round, 2 = semifinals, etc.)
            $table->foreignId('team_id')->nullable()->constrained()->nullOnDelete();
            $table->string('label')->nullable(); // Optional label like "Seed 1", "Winner of Match 1", etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('playoff_slots');
    }
};
