<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\ClientTier;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $onlyBanned = $request->boolean('banned');
        $search = trim((string) $request->query('q', ''));

        $clients = User::where('role', 'client')
            ->when($onlyBanned, fn ($q) => $q->whereNotNull('banned_at'))
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($w) use ($search) {
                    $w->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            // banned accounts first, then newest
            ->orderByRaw('banned_at IS NULL')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $bannedCount = User::where('role', 'client')->whereNotNull('banned_at')->count();

        return view('admin.clients', compact('clients', 'onlyBanned', 'search', 'bannedCount'));
    }

    /** Only an admin can lift a ban. The cancellation count starts again from 0. */
    public function unban(User $user)
    {
        abort_unless($user->isClient(), 404);

        $user->forceFill([
            'banned_at' => null,
            'ban_reason' => null,
            'cancel_strikes' => 0,
        ])->save();

        return back()->with('success', $user->full_name . ' has been unbanned.');
    }

    /** Manual ban (for a client the clinic does not trust). */
    public function ban(User $user)
    {
        abort_unless($user->isClient(), 404);

        $user->forceFill([
            'banned_at' => now(),
            'ban_reason' => 'Banned by admin',
        ])->save();

        return back()->with('success', $user->full_name . ' has been banned.');
    }
}
