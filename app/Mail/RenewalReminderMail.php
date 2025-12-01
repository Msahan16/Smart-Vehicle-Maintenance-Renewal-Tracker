<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RenewalReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $reminders;

    public function __construct($user, $reminders)
    {
        $this->user = $user;
        $this->reminders = $reminders;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Vehicle Renewal Reminder - Action Required',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.renewal-reminder',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
