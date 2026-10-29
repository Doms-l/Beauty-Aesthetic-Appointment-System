<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    public const DEFAULT_IMAGE = 'images/promo.jpg';

    /** The owner can choose 1 to 100 slots (empty = unlimited). */
    public const MAX_SLOTS = 100;

    protected $fillable = [
        'title', 'bar_text', 'image', 'service_id', 'is_active',
        'starts_at', 'ends_at', 'slots',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at'   => 'datetime',
        'slots'     => 'integer',
    ];

    private static ?Collection $active = null;

    protected static function booted(): void
    {
        // any change must show up straight away
        static::saved(fn () => self::$active = null);
        static::deleted(fn () => self::$active = null);
    }

    /**
     * Every promo that clients can see right now, oldest first:
     * switched on, already started, not ended and not fully booked.
     *
     * The home slideshow, the top announcement bar and the admin
     * dashboard slider all show these, one after the other.
     * An empty collection means nothing is shown.
     */
    public static function active(): Collection
    {
        return self::$active ??= static::query()
            ->running()
            ->withBookedCount()
            ->orderBy('id')
            ->get()
            ->reject(fn (self $promo) => $promo->isSoldOut())
            ->values();
    }

    /** Switched on and inside its start / end time. */
    public function scopeRunning(Builder $query): Builder
    {
        $now = now();

        return $query->where('is_active', true)
            ->where(fn ($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now))
            ->where(fn ($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>', $now));
    }

    /** Adds `booked_count` = bookings that still hold a slot (not cancelled). */
    public function scopeWithBookedCount(Builder $query): Builder
    {
        return $query->withCount([
            'appointments as booked_count' => fn ($q) => $q->where('status', '!=', 'cancelled'),
        ]);
    }

    /** Promos that have a time limit or a slot limit. */
    public function scopeLimited(Builder $query): Builder
    {
        return $query->where(fn ($q) => $q
            ->whereNotNull('slots')
            ->orWhereNotNull('starts_at')
            ->orWhereNotNull('ends_at'));
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Where "Book this promo" goes.
     * Clients land on the booking page with the linked Promo service
     * already selected; guests go to register; staff/admin stay on home.
     */
    public function bookingUrl(): string
    {
        if (! auth()->check()) {
            return route('register');
        }

        if (! auth()->user()->isClient()) {
            return route('home');
        }

        return $this->service_id
            ? route('client.appointments.create', ['service' => $this->service_id, 'promo' => $this->id])
            : route('client.appointments.create');
    }

    /* ------------------------------------------------------------------
     | Time limit and slots
     ------------------------------------------------------------------ */

    public function isLimited(): bool
    {
        return $this->slots !== null || $this->starts_at !== null || $this->ends_at !== null;
    }

    public function hasStarted(): bool
    {
        return $this->starts_at === null || $this->starts_at->lte(now());
    }

    public function hasEnded(): bool
    {
        return $this->ends_at !== null && $this->ends_at->lte(now());
    }

    /** Bookings that hold a slot (cancelled ones give the slot back). */
    public function slotsTaken(): int
    {
        return (int) ($this->booked_count
            ?? $this->appointments()->where('status', '!=', 'cancelled')->count());
    }

    /** null = unlimited. */
    public function slotsLeft(): ?int
    {
        return $this->slots === null ? null : max(0, $this->slots - $this->slotsTaken());
    }

    public function isSoldOut(): bool
    {
        return $this->slots !== null && $this->slotsLeft() === 0;
    }

    /**
     * Ids of services whose limited promo(s) can't be booked right now
     * (ended, full, hidden or not started). They are taken off the booking list.
     */
    public static function closedServiceIds(): array
    {
        return static::query()
            ->limited()
            ->whereNotNull('service_id')
            ->withBookedCount()
            ->get()
            ->groupBy('service_id')
            ->filter(fn ($group) => $group->doesntContain(fn (self $p) => $p->isBookable()))
            ->keys()
            ->all();
    }

    /** Can a client book it right now? */
    public function isBookable(): bool
    {
        return $this->is_active
            && $this->hasStarted()
            && ! $this->hasEnded()
            && ! $this->isSoldOut();
    }

    /** One word for the admin list. */
    public function getStatusLabelAttribute(): string
    {
        return match (true) {
            ! $this->is_active    => 'Hidden',
            $this->hasEnded()     => 'Ended',
            ! $this->hasStarted() => 'Scheduled',
            $this->isSoldOut()    => 'Fully booked',
            default               => 'Live',
        };
    }

    /** Longer sentence for the admin list. */
    public function getStatusTextAttribute(): string
    {
        return match ($this->status_label) {
            'Live'         => 'Live on the website',
            'Fully booked' => 'Fully booked (hidden from clients)',
            'Ended'        => 'Ended',
            'Scheduled'    => 'Scheduled: starts ' . $this->starts_at->format('M j, Y g:i A'),
            default        => 'Hidden',
        };
    }

    /** "Only 3 of 10 slots left · Ends Nov 5, 11:59 PM" (empty when unlimited). */
    public function getPerkLineAttribute(): string
    {
        $parts = [];

        if ($this->slots !== null) {
            $left = $this->slotsLeft();
            $parts[] = $left === 1
                ? 'Only 1 slot left'
                : "Only {$left} of {$this->slots} slots left";
        }

        if ($this->ends_at !== null) {
            $parts[] = 'Ends ' . $this->ends_at->format('M j, g:i A');
        }

        return implode(' · ', $parts);
    }

    public function getImageUrlAttribute(): string
    {
        return asset($this->image ?: self::DEFAULT_IMAGE);
    }

    public function getBarLineAttribute(): string
    {
        return $this->bar_text ?: $this->title;
    }
}
