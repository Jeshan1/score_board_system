<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'team_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    // Referee assigned matches
    public function matches()
    {
        return $this->hasMany(MatchModel::class, 'referee_id');
    }

    // roles 

    protected $casts = [
        'role' => 'string',
    ];
    
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isTeamManager(): bool
    {
        return $this->role === 'team_manager';
    }

    public function isReferee(): bool
    {
        return $this->role === 'referee';
    }
}
