<?php

namespace App\Models;

use App\Support\SocialPlatforms;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class SocialLink extends Model
{
    protected $fillable = ['platform', 'label', 'url', 'sort_order', 'is_active'];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    private static ?Collection $active = null;

    protected static function booted(): void
    {
        static::saved(fn () => self::$active = null);
        static::deleted(fn () => self::$active = null);
    }

    /**
     * The accounts shown in the footer, in order.
     * Never breaks a page: if the table does not exist yet, nothing is shown.
     */
    public static function active(): Collection
    {
        if (self::$active !== null) {
            return self::$active;
        }

        try {
            return self::$active = static::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get();
        } catch (\Throwable $e) {
            return self::$active = new Collection();
        }
    }

    /** The logo (SVG path) picked automatically for this platform. */
    public function getIconPathAttribute(): ?string
    {
        return SocialPlatforms::get($this->platform)['path'] ?? null;
    }

    public function getPlatformNameAttribute(): string
    {
        return SocialPlatforms::get($this->platform)['name'] ?? ucfirst((string) $this->platform);
    }

    public function getHrefAttribute(): string
    {
        return SocialPlatforms::href($this->platform, $this->url);
    }

    /** Phone and email links open the phone / mail app, so no new tab. */
    public function getOpensNewTabAttribute(): bool
    {
        return !in_array(SocialPlatforms::get($this->platform)['type'] ?? 'url', ['phone', 'email'], true);
    }
}
