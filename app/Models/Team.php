<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
        'name',
        'league_id',
    ];
    public function league()
    {
        return $this->belongsTo(League::class);
    }

    public function players()
    {
        return $this->hasMany(Player::class);
    }

    // Team events (goals + fouls)
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    // When team is victim of foul
    public function foulsReceived()
    {
        return $this->hasMany(Event::class, 'victim_team_id');
    }

    public function manager()
    {
        return $this->hasOne(User::class)->where('role', 'team_manager');
    }

    public function homeGames()
    {
        return $this->hasMany(Game::class, 'team_a_id');
    }

    public function awayGames()
    {
        return $this->hasMany(Game::class, 'team_b_id');
    }

}
