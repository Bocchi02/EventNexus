<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Client;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'organizer_id',
        'client_id',
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
        'gallery_images' => 'array',
    ];

    // Relationship: Event belongs to a User (organizer)
    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    // In App\Models\Event.php
    public function client()
    {
        return $this->belongsTo(user::class, 'client_id');
    }

    public function invitedGuests()
    {
        return $this->belongsToMany(User::class, 'event_guest')
                    ->withPivot('status', 'role')
                    ->withTimestamps();
    }

    public function registeredGuests()
    {
        return $this->belongsToMany(User::class, 'event_guest')
                    ->withPivot('status', 'role')
                    ->withTimestamps();
    }

    public function pendingGuests()
    {
        return $this->hasMany(PendingGuest::class);
    }

    public function guests()
    {
        return $this->belongsToMany(User::class, 'event_guests', 'event_id', 'user_id')
                ->withPivot('status') // ⬅️ CRUCIAL: Load the pivot data
                ->withTimestamps();
    }
}
