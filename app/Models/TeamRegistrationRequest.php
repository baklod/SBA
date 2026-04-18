<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamRegistrationRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'division_id',
        'logo',
        'status',
        'team_id',
        'reviewed_at',
        'reviewed_by',
    ];

    protected $casts = [
        'division_id' => 'integer',
        'team_id' => 'integer',
        'reviewed_by' => 'integer',
        'reviewed_at' => 'datetime',
    ];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
