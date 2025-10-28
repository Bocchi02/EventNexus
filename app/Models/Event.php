<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'organizer_id',
        'title',
        'description',
        'venue',
        'start_date',
        'end_date',
        'status',
        'cover_image',
        'gallery_images',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    // Relationship: Event belongs to a User (organizer)
    public function organizer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
