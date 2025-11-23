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

        // if(Schema::hasTable('events')) {
            Schema::create('events', function (Blueprint $table) {
                $table->id();
                $table->foreignId('match_id')->constrained('games')->cascadeOnDelete();
                $table->foreignId('team_id')->constrained('teams')->cascadeOnDelete();
                $table->foreignId('player_id')->nullable()->constrained('players')->nullOnDelete();
        
                $table->enum('type', ['goal', 'foul']);
                $table->enum('foul_type', ['yellow', 'red', 'technical'])->nullable();
    
                // Added: Victim Info
                $table->foreignId('victim_team_id')->nullable()->constrained('teams')->nullOnDelete();
    
                $table->foreignId('victim_player_id')->nullable()->constrained('players')->nullOnDelete();
    
                $table->integer('minute')->nullable(); //  Added: Minute in match
                $table->timestamps();
            });
        // }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
