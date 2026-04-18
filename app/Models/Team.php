<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo',
        'division_id',
        'wins',
        'losses',
        'points',
    ];

    protected $appends = ['win_percentage'];

    protected $casts = [
        'wins' => 'integer',
        'losses' => 'integer',
        'points' => 'integer',
    ];

    /**
     * Get the division this team belongs to
     */
    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    /**
     * Get the team's players
     */
    public function players()
    {
        return $this->hasMany(Player::class);
    }

    /**
     * Get matches where this team is team A
     */
    public function homeMatches()
    {
        return $this->hasMany(\App\Models\GameMatch::class, 'team_a_id');
    }

    /**
     * Get matches where this team is team B
     */
    public function awayMatches()
    {
        return $this->hasMany(\App\Models\GameMatch::class, 'team_b_id');
    }

    /**
     * Get all matches for this team
     */
    public function matches()
    {
        return \App\Models\GameMatch::where('team_a_id', $this->id)
            ->orWhere('team_b_id', $this->id);
    }

    /**
     * Calculate win percentage
     */
    public function getWinPercentageAttribute()
    {
        $totalGames = $this->wins + $this->losses;
        return $totalGames > 0 ? round(($this->wins / $totalGames) * 100, 2) : 0;
    }
}
