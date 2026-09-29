<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'name',
        'description',
        'price',
        'price_display',
        'duration_minutes',
        'is_available',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
    ];

    /**
     * A service can have many appointments.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Returns the price that should be displayed publicly.
     */
    public function getDisplayPriceAttribute(): string
    {
        if (!empty($this->price_display)) {
            return $this->price_display;
        }

        return '₱' . number_format((float) $this->price, 0);
    }
}