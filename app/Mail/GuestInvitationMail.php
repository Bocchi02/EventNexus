<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

use App\Models\PendingGuest;

class GuestInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $pending;

    /**
     * Create a new message instance.
     */
    public function __construct(PendingGuest $pending)
    {
        $this->pending = $pending;
    }

    public function build()
    {
        return $this->subject('You are invited to an event')
            ->markdown('emails.guest_invite', [
                'pending' => $this->pending,
                'link' => route('invitation.accept', $this->pending->token)
            ]);
    }



    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Guest Invitation Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.guest_invite',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
