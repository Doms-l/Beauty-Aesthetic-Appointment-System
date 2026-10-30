<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'service_id',
        'promo_id',
        'staff_id',
        'with_owner',
        'appointment_date',
        'appointment_time',
        'notes',
        'status',
        'receipt_number',
        'next_session_date',
        'archived_at',
        'admin_saved_at',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'next_session_date' => 'date',
        'with_owner' => 'boolean',
        'archived_at' => 'datetime',
        'admin_saved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    /** Finished visits of a package booking, in order (session 1, 2, ...). */
    public function sessions()
    {
        return $this->hasMany(AppointmentSession::class)->orderBy('session_no');
    }

    /** True when this booking is a package (its service has more than one session). */
    public function getIsPackageAttribute(): bool
    {
        return (bool) ($this->service && $this->service->is_package);
    }

    /** How many sessions of a package booking are already done. */
    public function getSessionsDoneAttribute(): int
    {
        return $this->relationLoaded('sessions')
            ? $this->sessions->count()
            : $this->sessions()->count();
    }

    /**
     * The day of the client's next visit for this booking.
     *  - normal service: the booked day
     *  - package, nothing done yet: the booked day (session 1)
     *  - package, in progress: the day the clinic planned for the next
     *    session, or null while it is not set yet
     *  - finished / cancelled / archived package: null
     */
    public function getNextVisitDateAttribute(): ?\Illuminate\Support\Carbon
    {
        if (! $this->is_package) {
            return $this->appointment_date;
        }

        if (! $this->canLogSession()) {
            return null;
        }

        return $this->sessions_done === 0
            ? $this->appointment_date
            : $this->next_session_date;
    }

    /**
     * Package bookings of a client that have a planned next visit
     * (today or later). Used for the avatar badge and the profile page.
     */
    public static function upcomingPackageVisits(int $userId)
    {
        return static::with(['service', 'sessions'])
            ->where('user_id', $userId)
            ->whereNotIn('status', ['cancelled', 'completed'])
            ->whereNull('archived_at')
            ->whereNotNull('next_session_date')
            ->whereDate('next_session_date', '>=', today())
            ->orderBy('next_session_date')
            ->get()
            ->filter(fn (Appointment $a) => $a->is_package && $a->canLogSession() && $a->sessions_done >= 1)
            ->values();
    }

    /** True when a visit can still be logged on this booking. */
    public function canLogSession(): bool
    {
        return $this->is_package
            && $this->archived_at === null
            && $this->status !== 'cancelled'
            && $this->sessions_done < (int) $this->service->sessions;
    }

    /** The promo this booking used a slot of (if any). */
    public function promo()
    {
        return $this->belongsTo(Promo::class);
    }

    /**
     * Appointments that are not archived.
     */
    public function scopeActive($query)
    {
        return $query->whereNull('archived_at');
    }

    /**
     * Appointments that count in the analytics:
     * not cancelled AND not archived.
     */
    public function scopeCounted($query)
    {
        return $query->whereNull('archived_at')
            ->where('status', '!=', 'cancelled');
    }

    /**
     * Who is doing the service (for display).
     */
    public function getProviderLabelAttribute(): string
    {
        if ($this->staff_id && $this->staff && $this->staff->user) {
            return $this->staff->user->full_name;
        }

        if ($this->with_owner) {
            return 'Owner';
        }

        return 'Any available';
    }
}
