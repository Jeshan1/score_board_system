<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = [
        'name',
        'team_id',
        'number',
        'position',
        'goals',
        'fouls'
    ];
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function foulsReceived()
    {
        return $this->hasMany(Event::class, 'victim_player_id');
    }
}
