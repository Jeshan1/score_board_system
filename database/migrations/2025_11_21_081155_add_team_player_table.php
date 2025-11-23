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
        Schema::create('team_player', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID for pivot table
            $table->foreignId('team_id')->constrained('teams')->cascadeOnDelete();  // Foreign key to teams
            $table->foreignId('player_id')->constrained('players')->cascadeOnDelete();  // Foreign key to players
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_player');
    }
};
