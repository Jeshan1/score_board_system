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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            // Linking teams
            $table->foreignId('team_a_id')->constrained('teams')->cascadeOnDelete();
            $table->foreignId('team_b_id')->constrained('teams')->cascadeOnDelete();

            // League
            $table->foreignId('league_id')->nullable()->constrained('leagues')->nullOnDelete();

            // Referee assigned
            $table->foreignId('referee_id')->nullable()->constrained('users')->nullOnDelete();

            $table->date('match_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();

            // Match status
            $table->enum('status', ['scheduled', 'ongoing', 'paused', 'completed'])->default('scheduled');

            // Live scores
            $table->integer('team_a_score')->default(0);
            $table->integer('team_b_score')->default(0);

        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
