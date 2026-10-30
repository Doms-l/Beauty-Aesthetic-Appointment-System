<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * One finished visit of a package booking (session 2 of 4, ...).
 */
class AppointmentSession extends Model
{
    protected $fillable = [
        'appointment_id',
        'session_no',
        'session_date',
        'session_time',
        'staff_id',
        'receipt_number',
    ];

    protected $casts = [
        'session_date' => 'date',
        'session_no' => 'integer',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    /** Name written on paper for this visit ('' when nobody is known). */
    public function getStaffLabelAttribute(): string
    {
        if ($this->staff && $this->staff->user) {
            return $this->staff->user->full_name;
        }

        return $this->appointment && $this->appointment->with_owner ? 'Owner' : '';
    }
}
