<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'name',
        'jersey_number',
        'position',
        'photo',
    ];

    /**
     * Get the team that owns the player
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get the player's stats
     */
    public function stats()
    {
        return $this->hasMany(PlayerStat::class);
    }

    /**
     * Get total games played
     */
    public function getGamesPlayedAttribute()
    {
        return $this->stats()
            ->where('played', true)
            ->distinct()
            ->count('match_id');
    }

    /**
     * Get total points
     */
    public function getTotalPointsAttribute()
    {
        return $this->stats()->sum('points');
    }

    /**
     * Get total assists
     */
    public function getTotalAssistsAttribute()
    {
        return $this->stats()->sum('assists');
    }

    /**
     * Get total rebounds
     */
    public function getTotalReboundsAttribute()
    {
        return $this->stats()->sum('rebounds');
    }

    /**
     * Get highest points in a single game
     */
    public function getHighestPointsAttribute()
    {
        return $this->stats()->max('points') ?? 0;
    }
}
