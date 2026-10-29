<?php

namespace App\Support;

use App\Models\Promo;
use App\Models\Service;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Validation\ValidationException;

/**
 * Claims one slot of a limited promo when a client books its service.
 *
 * Call it INSIDE a DB::transaction(): the promo rows are locked, so when
 * two clients book the last slot at the same moment only one of them wins.
 *
 *  - service has no limited promo  -> returns null (normal booking)
 *  - a promo is running with slots -> returns that promo (save promo_id)
 *  - promo ended / full / hidden / not started yet, or the client already
 *    used it -> throws a ValidationException with a friendly message
 */
class PromoSlots
{
    public static function claim(Service $service, User $user): ?Promo
    {
        $promos = Promo::query()
            ->where('service_id', $service->id)
            ->limited()
            ->orderBy('id')
            ->lockForUpdate()
            ->get();

        if ($promos->isEmpty()) {
            return null;
        }

        $open = $promos->filter(fn (Promo $promo) => $promo->isBookable());

        if ($open->isEmpty()) {
            self::fail(self::closedMessage($promos->first()));
        }

        // one slot per client for each promo (cancelling gives it back)
        foreach ($open as $promo) {
            $already = Appointment::where('user_id', $user->id)
                ->where('promo_id', $promo->id)
                ->where('status', '!=', 'cancelled')
                ->exists();

            if (! $already) {
                return $promo;
            }
        }

        self::fail('You already booked this promo. Each client can avail it once.');
    }

    private static function closedMessage(Promo $promo): string
    {
        return match (true) {
            ! $promo->is_active    => 'This promo is not available right now.',
            $promo->hasEnded()     => 'Sorry, this promo has ended.',
            ! $promo->hasStarted() => 'This promo has not started yet. It starts '
                                      . $promo->starts_at->format('M j, g:i A') . '.',
            default                => "Sorry, all {$promo->slots} promo slots are already taken.",
        };
    }

    private static function fail(string $message): never
    {
        throw ValidationException::withMessages(['service_id' => $message]);
    }
}
