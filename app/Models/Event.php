<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'event_date',
        'event_time',
        'location',
        'posted_by',
        'is_published',
    ];

    protected $casts = [
        'event_date' => 'date',
        'is_published' => 'boolean',
    ];

    /**
     * Get the user who posted the event.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }
}
