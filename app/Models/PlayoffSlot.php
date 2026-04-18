<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayoffSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'division_id',
        'bracket_side',
        'position',
        'round',
        'team_id',
        'label',
    ];

    protected $casts = [
        'position' => 'integer',
        'round' => 'integer',
    ];

    /**
     * Get the division this playoff slot belongs to
     */
    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    /**
     * Get the team in this playoff slot
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
