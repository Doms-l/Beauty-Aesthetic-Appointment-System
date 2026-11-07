<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** One extra service added to an existing appointment (same visit). */
class AppointmentExtra extends Model
{
    protected $table = 'visit_extra_services';

    protected $fillable = ['appointment_id', 'service_id', 'price'];

    protected $casts = ['price' => 'decimal:2'];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
