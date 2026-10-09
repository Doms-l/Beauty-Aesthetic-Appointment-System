<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    public const DEFAULT_IMAGE = 'images/promo.jpg';

    protected $fillable = ['title', 'bar_text', 'image', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    private static ?self $current = null;

    /**
     * The one promo row (a default one if the table is still empty).
     */
    public static function current(): self
    {
        return self::$current ??= static::query()->first() ?? new static([
            'title'     => 'Retouch/Recolor Microbrows — ₱999 with FREE Lashes',
            'bar_text'  => 'Retouch/Recolor Microbrows only ₱999 with FREE Lashes',
            'is_active' => true,
        ]);
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
