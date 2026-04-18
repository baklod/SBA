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
        Schema::table('matches', function (Blueprint $table) {
            $table->enum('quarter', ['1st', '2nd', '3rd', '4th', 'OT', 'finished'])->default('1st')->after('status');
        });

        Schema::table('player_stats', function (Blueprint $table) {
            $table->integer('fouls')->default(0)->after('rebounds');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropColumn('quarter');
        });

        Schema::table('player_stats', function (Blueprint $table) {
            $table->dropColumn('fouls');
        });
    }
};
