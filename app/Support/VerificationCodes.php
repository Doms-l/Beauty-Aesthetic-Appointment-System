<?php

namespace App\Support;

use App\Mail\VerificationCodeMail;
use App\Models\VerificationCode;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

/**
 * Emailed 6-digit codes used for:
 *  - confirming a new account's email address (register)
 *  - resetting a forgotten password (reset)
 *
 * Codes are stored hashed, expire after TTL_MINUTES, and are locked
 * after MAX_ATTEMPTS wrong guesses.
 */
class VerificationCodes
{
    public const PURPOSE_REGISTER = 'register';
    public const PURPOSE_RESET = 'reset';

    public const TTL_MINUTES = 10;
    public const RESEND_SECONDS = 60;
    public const MAX_ATTEMPTS = 5;

    public static function normalize(string $email): string
    {
        return Str::lower(trim($email));
    }

    /**
     * Create a fresh code, store it, and email it.
     * Throws if the email could not be sent (nothing is kept in that case).
     */
    public static function issue(string $email, string $purpose, ?array $payload = null): void
    {
        $email = self::normalize($email);
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $record = VerificationCode::updateOrCreate(
            ['email' => $email, 'purpose' => $purpose],
            [
                'code_hash' => Hash::make($code),
                'payload' => $payload ? Crypt::encryptString(json_encode($payload)) : null,
                'attempts' => 0,
                'expires_at' => now()->addMinutes(self::TTL_MINUTES),
                'last_sent_at' => now(),
            ]
        );

        try {
            Mail::to($email)->send(new VerificationCodeMail($code, $purpose, self::TTL_MINUTES));
        } catch (Throwable $e) {
            $record->delete();
            throw $e;
        }
    }

    /**
     * Send a new code for an existing request, keeping the saved payload.
     * Returns false when there is nothing to resend.
     */
    public static function resend(string $email, string $purpose): bool
    {
        $record = self::find($email, $purpose);

        if (! $record) {
            return false;
        }

        $payload = $record->payload
            ? json_decode(Crypt::decryptString($record->payload), true)
            : null;

        self::issue($email, $purpose, $payload);

        return true;
    }

    public static function secondsUntilResend(string $email, string $purpose): int
    {
        $record = self::find($email, $purpose);

        if (! $record) {
            return 0;
        }

        $wait = $record->last_sent_at->copy()->addSeconds(self::RESEND_SECONDS);

        return max(0, (int) now()->diffInSeconds($wait, false));
    }

    /**
     * Check a code the person typed in.
     *
     * @return array{ok: bool, error: ?string, payload: ?array}
     */
    public static function verify(string $email, string $purpose, string $code): array
    {
        $record = self::find($email, $purpose);

        if (! $record || $record->expires_at->isPast()) {
            return self::fail('That code is invalid or has expired. Please request a new one.');
        }

        if ($record->attempts >= self::MAX_ATTEMPTS) {
            return self::fail('Too many wrong attempts. Please request a new code.');
        }

        if (! Hash::check($code, $record->code_hash)) {
            $record->increment('attempts');

            $left = self::MAX_ATTEMPTS - $record->attempts;

            return self::fail($left > 0
                ? "Incorrect code. You have {$left} attempt(s) left."
                : 'Too many wrong attempts. Please request a new code.');
        }

        $payload = $record->payload
            ? json_decode(Crypt::decryptString($record->payload), true)
            : null;

        return ['ok' => true, 'error' => null, 'payload' => $payload];
    }

    public static function forget(string $email, string $purpose): void
    {
        VerificationCode::where('email', self::normalize($email))
            ->where('purpose', $purpose)
            ->delete();
    }

    public static function mask(string $email): string
    {
        [$name, $domain] = array_pad(explode('@', $email, 2), 2, '');

        return Str::substr($name, 0, 2) . str_repeat('*', max(1, Str::length($name) - 2)) . '@' . $domain;
    }

    private static function find(string $email, string $purpose): ?VerificationCode
    {
        return VerificationCode::where('email', self::normalize($email))
            ->where('purpose', $purpose)
            ->first();
    }

    private static function fail(string $message): array
    {
        return ['ok' => false, 'error' => $message, 'payload' => null];
    }
}
