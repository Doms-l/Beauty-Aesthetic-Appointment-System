<?php

namespace App\Mail;

use App\Support\VerificationCodes;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class VerificationCodeMail extends Mailable
{
    public function __construct(
        public string $code,
        public string $purpose,
        public int $minutes,
    ) {
    }

    public function envelope(): Envelope
    {
        $subject = $this->purpose === VerificationCodes::PURPOSE_RESET
            ? 'Your M. Cares password reset code'
            : 'Your M. Cares email verification code';

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.verification-code');
    }
}
