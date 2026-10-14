<?php

namespace App\Support;

use App\Models\Appointment;
use App\Models\User;

/**
 * Works out a client's level (Member / Glow Client / VIP) and the booking
 * limits that go with it. The rules live in config/loyalty.php.
 */
class ClientTier
{
    public function __construct(
        public string $key,
        public array $level,
        public int $completed,
        public ?array $next = null,
    ) {
    }

    public function name(): string
    {
        return $this->level['name'];
    }

    public function limit(string $setting): int
    {
        return (int) $this->level[$setting];
    }

    /** Completed visits still needed for the next level (null at the top). */
    public function visitsToNext(): ?int
    {
        return $this->next ? max(0, $this->next['min_completed'] - $this->completed) : null;
    }

    public function nextName(): ?string
    {
        return $this->next['name'] ?? null;
    }

    /**
     * Pure rule: which level does this many completed visits give?
     * Kept free of the database so it is easy to test.
     */
    public static function fromCompleted(int $completed, array $levels): self
    {
        $key = array_key_first($levels);

        foreach ($levels as $levelKey => $level) {
            if ($completed >= $level['min_completed']) {
                $key = $levelKey;
            }
        }

        $keys = array_keys($levels);
        $nextKey = $keys[array_search($key, $keys, true) + 1] ?? null;

        return new self($key, $levels[$key], $completed, $nextKey ? $levels[$nextKey] : null);
    }

    public static function for(User $user): self
    {
        $completed = Appointment::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();

        return self::fromCompleted($completed, config('loyalty.levels'));
    }

    /**
     * Check a new booking against the client's limits.
     * Returns an error message, or null when the booking is allowed.
     */
    public function bookingError(User $user, string $appointmentDate): ?string
    {
        // 1. Too many cancellations lately (fake or abandoned bookings).
        $cancelled = Appointment::where('user_id', $user->id)
            ->where('status', 'cancelled')
            ->where('updated_at', '>=', now()->subDays(30))
            ->count();

        if ($cancelled >= $this->limit('max_cancels_30d')) {
            return 'Your account is paused from booking because of too many cancelled appointments '
                . 'in the last 30 days. Please contact the clinic to continue.';
        }

        // 2. Too many bookings waiting at the same time.
        $active = Appointment::where('user_id', $user->id)
            ->whereNull('archived_at')
            ->whereIn('status', ['pending', 'confirmed', 'rescheduled'])
            ->whereDate('appointment_date', '>=', now()->toDateString())
            ->count();

        if ($active >= $this->limit('max_active')) {
            return "As a {$this->name()}, you can have up to {$this->limit('max_active')} "
                . ($this->limit('max_active') === 1 ? 'upcoming appointment' : 'upcoming appointments')
                . ' at a time. Please wait until it is completed or cancel one first.'
                . $this->upgradeHint();
        }

        // 3. Too many requests in a single day (spam protection).
        $today = Appointment::where('user_id', $user->id)
            ->whereDate('created_at', now()->toDateString())
            ->count();

        if ($today >= $this->limit('max_per_day')) {
            return "As a {$this->name()}, you can send up to {$this->limit('max_per_day')} "
                . 'booking requests per day. Please try again tomorrow.';
        }

        // 4. Booking too far ahead.
        $latest = now()->addDays($this->limit('advance_days'))->toDateString();

        if ($appointmentDate > $latest) {
            return "As a {$this->name()}, you can book up to {$this->limit('advance_days')} days ahead."
                . $this->upgradeHint();
        }

        return null;
    }

    private function upgradeHint(): string
    {
        $left = $this->visitsToNext();

        if ($left === null || $left <= 0) {
            return '';
        }

        return " Complete {$left} more " . ($left === 1 ? 'visit' : 'visits')
            . " to become a {$this->nextName()} and get higher limits.";
    }
}
