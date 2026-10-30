<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/** "A client just booked" - for the owner and for staff. */
class NewBookingMail extends Mailable
{
    public function __construct(
        public Appointment $appointment,
        public string $audience = 'staff',
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New booking: ' . $this->appointment->service->name
                . ' - ' . $this->appointment->appointment_date->format('M d, Y'),
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.new-booking', with: [
            'appointment' => $this->appointment,
            'audience'    => $this->audience,
            'link'        => $this->audience === 'owner'
                ? route('admin.appointments')
                : route('staff.dashboard'),
        ]);
    }
}
