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
        'staff_id',
        'with_owner',
        'appointment_date',
        'appointment_time',
        'notes',
        'status',
        'archived_at',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'with_owner' => 'boolean',
        'archived_at' => 'datetime',
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
