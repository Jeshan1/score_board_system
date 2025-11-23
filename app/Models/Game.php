<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $table = 'games';   // when i want to create by Match -m then laravel not allowed to create Match table like this way due to Match is a reserved in laravel

    protected $fillable = [
        'team_a_id',
        'team_b_id',
        'league_id',
        'referee_id',
        'match_date',
        'start_time',
        'end_time',
        'status',
        'team_a_score',
        'team_b_score',
        'team_a_fouls',
        'team_b_fouls',
    ];

    // protected $with = ['teamA', 'teamB'];
    
    // protected $appends = ['team_a_players', 'team_b_players'];
    public function league()
    {
        return $this->belongsTo(League::class);
    }

    public function teamA()
    {
        return $this->belongsTo(Team::class, 'team_a_id');
    }

    public function teamB()
    {
        return $this->belongsTo(Team::class, 'team_b_id');
    }

    public function referee()
    {
        return $this->belongsTo(User::class, 'referee_id');
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'match_id', 'id');
    }


    // public function players()
    // {
    //     return $this->hasMany(Player::class, 'team_id', 'team_a_id')
    //                 ->orWhere('team_id', 'team_b_id');
    // }


    // You can also create custom accessors for teamAPlayers and teamBPlayers:
    public function teamAPlayers()
    {
        return $this->hasMany(Player::class, 'team_id', 'team_a_id');
    }
    
    public function teamBPlayers()
    {
        return $this->hasMany(Player::class, 'team_id', 'team_b_id');
    }

   // This appends team_a_players array in API response
//    public function getTeamAPlayersAttribute()
//    {
//        return $this->teamAPlayers()->get();
//    }

//    public function getTeamBPlayersAttribute()
//    {
//        return $this->teamBPlayers()->get();
//    }

//    // Optional: also append team objects (if not using $with)
//    public function getTeamAAttribute()
//    {
//        return $this->teamA;
//    }

//    public function getTeamBAttribute()
//    {
//        return $this->teamB;
//    }
    
}
