<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    public const DEFAULT_IMAGE = 'images/promo.jpg';

    protected $fillable = ['title', 'bar_text', 'image', 'service_id', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    private static ?Collection $active = null;

    protected static function booted(): void
    {
        // any change must show up straight away
        static::saved(fn () => self::$active = null);
        static::deleted(fn () => self::$active = null);
    }

    /**
     * Every promo that is switched on, oldest first.
     *
     * The home slideshow, the top announcement bar and the admin
     * dashboard slider all show these, one after the other.
     * An empty collection means nothing is shown.
     */
    public static function active(): Collection
    {
        return self::$active ??= static::query()
            ->where('is_active', true)
            ->orderBy('id')
            ->get();
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
            ? route('client.appointments.create', ['service' => $this->service_id])
            : route('client.appointments.create');
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
