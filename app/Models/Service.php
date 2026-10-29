<?php

namespace App\Models;

use App\Support\ServiceCatalog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'name',
        'description',
        'image',
        'price',
        'price_display',
        'duration_minutes',
        'sessions',
        'is_available',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sessions' => 'integer',
        'is_available' => 'boolean',
    ];

    /**
     * True when one purchase covers more than one session (a package).
     */
    public function getIsPackageAttribute(): bool
    {
        return (int) $this->sessions > 1;
    }

    /**
     * The part of the price that belongs to one session.
     * A single-session service simply returns its full price,
     * a package returns price / sessions (e.g. 3999 / 4 = 999.75).
     */
    public function getPerSessionPriceAttribute(): float
    {
        return (float) $this->price / max(1, (int) $this->sessions);
    }

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

    /**
     * Path of the photo inside /public (uploaded photo first,
     * otherwise the matching photo from the category folder).
     */
    public function getImagePathAttribute(): ?string
    {
        return $this->image ?: ServiceCatalog::imageFor($this->name);
    }

    /**
     * Full URL of the photo, or null if the service has none.
     */
    public function getImageUrlAttribute(): ?string
    {
        return ServiceCatalog::url($this->image_path);
    }

    /**
     * Description typed in the admin, or the built-in one for this service.
     */
    public function getEffectiveDescriptionAttribute(): ?string
    {
        return $this->description ?: ServiceCatalog::descriptionFor($this->name);
    }
}
