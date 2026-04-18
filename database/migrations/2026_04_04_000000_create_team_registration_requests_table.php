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
        Schema::create('team_registration_requests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('division_id')->nullable()->constrained()->nullOnDelete();
            $table->string('logo')->nullable();
            $table->string('status')->default('pending');
            $table->foreignId('team_id')->nullable()->constrained('teams')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_registration_requests');
    }
};
