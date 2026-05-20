<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $temporaryPassword
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'GovLog Sentinel - Admin Account Created');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.admin-credentials');
    }
}
