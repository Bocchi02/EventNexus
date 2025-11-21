<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingGuest extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'firstname',
        'middlename',
        'lastname',
        'email',
        'status', //pending, accepted, expired
        'token'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class);
    }
}
