<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;  

class League extends Model
{
    use SoftDeletes;
    protected $table = 'leagues';

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
    ];
    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    public function matches()
    {
        return $this->hasMany(MatchModel::class);
    }
}
