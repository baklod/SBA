<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameMatch extends Model
{
    use HasFactory;

    protected $table = 'matches';

    protected $fillable = [
        'team_a_id',
        'team_b_id',
        'match_date',
        'venue',
        'match_time',
        'team_a_score',
        'team_b_score',
        'status',
        'quarter',
    ];

    protected $casts = [
        'match_date' => 'datetime',
        'team_a_score' => 'integer',
        'team_b_score' => 'integer',
    ];

    /**
     * Get team A
     */
    public function teamA()
    {
        return $this->belongsTo(Team::class, 'team_a_id');
    }

    /**
     * Get team B
     */
    public function teamB()
    {
        return $this->belongsTo(Team::class, 'team_b_id');
    }

    /**
     * Get player stats for this match
     */
    public function playerStats()
    {
        return $this->hasMany(PlayerStat::class, 'match_id');
    }

    /**
     * Get the winner of the match
     */
    public function getWinnerAttribute()
    {
        if ($this->status !== 'finished' || is_null($this->team_a_score) || is_null($this->team_b_score)) {
            return null;
        }

        if ($this->team_a_score > $this->team_b_score) {
            return $this->teamA;
        } elseif ($this->team_b_score > $this->team_a_score) {
            return $this->teamB;
        }

        return null; // Draw
    }
}
