<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * The "The Owner" card clients see on the booking page.
 * There is only ever one row; the admin edits it on the Staff page.
 */
class OwnerProfile extends Model
{
    public const DEFAULT_NAME = 'The Owner';
    public const DEFAULT_DESCRIPTION = 'Served personally by the owner';

    protected $fillable = ['name', 'description', 'photo'];

    public static function current(): self
    {
        return static::query()->first() ?? static::create([
            'name' => self::DEFAULT_NAME,
            'description' => self::DEFAULT_DESCRIPTION,
        ]);
    }

    public function getPhotoUrlAttribute(): ?string
    {
        // saved in public/images/owner; asset() follows the address the site is opened on
        return $this->photo ? asset($this->photo) : null;
    }
}
