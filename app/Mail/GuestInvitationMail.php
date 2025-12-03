<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Event;

class GuestInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $event;
    public $password;

    public function __construct(User $user, Event $event, $password)
    {
        $this->user = $user;
        $this->event = $event;
        $this->password = $password;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to EventNexus - Account Created',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.guest_invite',
            with: [
                'loginUrl' => route('login'),
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}