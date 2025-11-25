<?php

namespace App\Mail;

use App\Models\Event;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class EventRSVP extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $user;

    public function __construct(Event $event, User $user)
    {
        $this->event = $event;
        $this->user = $user;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'RSVP Requested: ' . $this->event->title,
        );
    }

    public function content(): Content
    {
        // Generate Secure Signed URLs
        $acceptUrl = URL::signedRoute('rsvp.respond', [
            'event' => $this->event->id,
            'user' => $this->user->id,
            'status' => 'accepted'
        ]);

        $declineUrl = URL::signedRoute('rsvp.respond', [
            'event' => $this->event->id,
            'user' => $this->user->id,
            'status' => 'declined'
        ]);

        return new Content(
            view: 'emails.rsvp', // We create this view next
            with: [
                'acceptUrl' => $acceptUrl,
                'declineUrl' => $declineUrl,
            ],
        );
    }
}