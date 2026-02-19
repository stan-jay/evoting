<?php

namespace App\Mail;

use App\Models\OrganizationInvite;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrganizationInviteMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public OrganizationInvite $invite)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Invitation to Join ' . ($this->invite->organization?->name ?? config('app.name'))
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.organization-invite',
            with: [
                'invite' => $this->invite,
                'registerUrl' => route('register', ['token' => $this->invite->token]),
            ]
        );
    }
}
