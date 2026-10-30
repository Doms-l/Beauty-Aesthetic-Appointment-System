<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/** "Your booking is confirmed" - for the client. */
class BookingConfirmedMail extends Mailable
{
    public function __construct(public Appointment $appointment)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Your M. Care booking is confirmed');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.booking-confirmed', with: [
            'appointment' => $this->appointment,
        ]);
    }
}
