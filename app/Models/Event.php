<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'match_id', 'team_id', 'player_id',
        'type', 'foul_type',
        'victim_team_id', 'victim_player_id',
        'minute'
    ];

    public function match()
    {
        return $this->belongsTo(MatchModel::class);
    }

    // Offending team (goal or foul done by this team)
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    // Offending player (the one who scored or fouled)
    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    // Victim team (team receiving foul)
    public function victimTeam()
    {
        return $this->belongsTo(Team::class, 'victim_team_id');
    }

    // Victim player
    public function victimPlayer()
    {
        return $this->belongsTo(Player::class, 'victim_player_id');
    }
}
