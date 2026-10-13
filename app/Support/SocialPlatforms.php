<?php

namespace App\Support;

/**
 * The "automatic logo generator" for the footer social accounts.
 *
 * Give it a link, an email or a phone number and it works out the platform
 * (instagram.com -> Instagram, t.me -> Telegram, 0915... -> Phone, ...) and
 * returns the matching logo. The list of platforms and their logos lives in
 * SocialPlatformData.php.
 */
class SocialPlatforms
{
    private static ?array $all = null;

    /** Every supported platform: key => [name, type, domains, path]. */
    public static function all(): array
    {
        return self::$all ??= require __DIR__ . '/SocialPlatformData.php';
    }

    public static function get(?string $key): ?array
    {
        return self::all()[$key] ?? null;
    }

    /** Dropdown options: key => name. */
    public static function options(): array
    {
        return array_map(fn (array $p) => $p['name'], self::all());
    }

    /**
     * Guess the platform from whatever the admin typed.
     */
    public static function detect(?string $value): string
    {
        $value = trim((string) $value);

        if ($value === '') {
            return 'website';
        }

        // email address
        if (!str_contains($value, '/') && filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return 'email';
        }

        // phone number (digits, spaces, dashes, brackets, optional +)
        if (preg_match('/^\+?[0-9][0-9\s\-().]{5,24}$/', $value)) {
            return 'phone';
        }

        // a link: compare its domain with the known platforms
        $host = self::host($value);

        if ($host !== '') {

            foreach (self::all() as $key => $platform) {

                foreach ($platform['domains'] as $domain) {

                    if ($host === $domain || str_ends_with($host, '.' . $domain)) {
                        return $key;
                    }
                }
            }
        }

        return 'website';
    }

    /**
     * Clean what the admin typed and check it matches the platform type.
     * Returns [value, errorMessage]; errorMessage is null when it is fine.
     */
    public static function clean(string $platform, string $value): array
    {
        $type  = self::get($platform)['type'] ?? 'url';
        $value = trim($value);

        if ($type === 'phone') {
            return preg_match('/^\+?[0-9][0-9\s\-().]{5,24}$/', $value)
                ? [$value, null]
                : [$value, 'Enter a phone number, for example 09155168312.'];
        }

        if ($type === 'email') {
            return filter_var($value, FILTER_VALIDATE_EMAIL)
                ? [$value, null]
                : [$value, 'Enter a valid email address.'];
        }

        // links: add https:// when it is missing, and only allow web links
        if (!preg_match('#^[a-z][a-z0-9+.\-]*://#i', $value)) {
            $value = 'https://' . ltrim($value, '/');
        }

        $scheme = strtolower((string) parse_url($value, PHP_URL_SCHEME));

        if (!in_array($scheme, ['http', 'https'], true)
            || filter_var($value, FILTER_VALIDATE_URL) === false
            || !str_contains((string) parse_url($value, PHP_URL_HOST), '.')) {

            return [$value, 'Enter a valid link, for example https://www.instagram.com/yourpage'];
        }

        return [$value, null];
    }

    /** The href the footer uses for a saved value. */
    public static function href(string $platform, string $value): string
    {
        $type = self::get($platform)['type'] ?? 'url';

        return match ($type) {
            'phone' => 'tel:' . preg_replace('/[^0-9+]/', '', $value),
            'email' => 'mailto:' . $value,
            default => $value,
        };
    }

    private static function host(string $value): string
    {
        if (!preg_match('#^[a-z][a-z0-9+.\-]*://#i', $value)) {
            $value = 'https://' . ltrim($value, '/');
        }

        $host = strtolower((string) parse_url($value, PHP_URL_HOST));

        return preg_replace('/^www\./', '', $host) ?? '';
    }
}
