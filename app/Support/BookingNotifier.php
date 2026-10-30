<?php

namespace App\Support;

use App\Mail\BookingConfirmedMail;
use App\Mail\NewBookingMail;
use App\Models\Appointment;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

/**
 * Booking emails. They work even when nobody has the website open,
 * because they go to the real inbox (Gmail etc.).
 *
 *  - newBooking():  client booked  -> owner + the staff member(s) who can take it
 *  - confirmed():   admin/staff approved -> the client
 *
 * A failed email is logged but never stops the booking itself.
 */
class BookingNotifier
{
    public static function newBooking(Appointment $appointment): void
    {
        $appointment->loadMissing(['user', 'service', 'staff.user']);

        $recipients = [];

        foreach (self::ownerEmails() as $email) {
            $recipients[$email] = 'owner';
        }

        foreach (self::staffEmails($appointment) as $email) {
            $recipients[$email] ??= 'staff';   // the owner mail wins when it is the same address
        }

        foreach ($recipients as $email => $audience) {
            self::send($email, new NewBookingMail($appointment, $audience));
        }
    }

    public static function confirmed(Appointment $appointment): void
    {
        $appointment->loadMissing(['user', 'service', 'staff.user']);

        $email = $appointment->user?->email;

        if ($email) {
            self::send($email, new BookingConfirmedMail($appointment));
        }
    }

    /** @return array<int,string> */
    private static function ownerEmails(): array
    {
        $configured = (string) config('booking.owner_email');

        $emails = $configured !== ''
            ? array_map('trim', explode(',', $configured))
            : User::where('role', 'admin')->pluck('email')->all();

        return self::clean($emails);
    }

    /** @return array<int,string> */
    private static function staffEmails(Appointment $appointment): array
    {
        if ($appointment->staff_id) {
            return self::clean([$appointment->staff?->user?->email]);
        }

        // "Owner only" bookings do not bother the staff
        if ($appointment->with_owner) {
            return [];
        }

        // "No preference": any available staff member may take it
        return self::clean(
            Staff::with('user')->where('is_available', true)->get()
                ->map(fn ($s) => $s->user?->email)->all()
        );
    }

    /** @param array<int,?string> $emails @return array<int,string> */
    private static function clean(array $emails): array
    {
        return collect($emails)
            ->filter()
            ->map(fn ($e) => Str::lower(trim($e)))
            ->filter(fn ($e) => filter_var($e, FILTER_VALIDATE_EMAIL))
            ->unique()
            ->values()
            ->all();
    }

    private static function send(string $to, $mailable): void
    {
        try {
            Mail::to($to)->send($mailable);
        } catch (Throwable $e) {
            report($e);
        }
    }
}
