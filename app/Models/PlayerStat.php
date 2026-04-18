<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerStat extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'match_id',
        'points',
        'assists',
        'rebounds',
        'fouls',
        'blocks',
        'steals',
        'played',
    ];

    protected $casts = [
        'points' => 'integer',
        'assists' => 'integer',
        'rebounds' => 'integer',
        'fouls' => 'integer',
        'blocks' => 'integer',
        'steals' => 'integer',
        'played' => 'boolean',
    ];

    /**
     * Get the player that owns the stat
     */
    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    /**
     * Get the match that owns the stat
     */
    public function match()
    {
        return $this->belongsTo(\App\Models\GameMatch::class, 'match_id');
    }
}
