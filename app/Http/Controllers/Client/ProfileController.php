<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        return view('client.profile', [
            'user' => $request->user()
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'first_name' => [
                'required',
                'string',
                'max:100'
            ],

            'last_name' => [
                'required',
                'string',
                'max:100'
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email,' . $user->id
            ],

            'phone' => ['required', 'digits:11'],
            'date_of_birth' => [
                'nullable',
                'date',
                'before:today'
            ],

            'address' => [
                'nullable',
                'string',
                'max:1000'
            ],

            'profile_picture' => [
    'nullable',
    'file',
    'mimetypes:image/jpeg,image/png,image/webp',
    'max:5120',
],
        ]);

        if ($request->hasFile('profile_picture')) {

            if ($user->profile_picture) {
                Storage::disk('public')->delete(
                    $user->profile_picture
                );
            }

            $validated['profile_picture'] =
                $request->file('profile_picture')
                    ->store('profile-pictures', 'public');
        }

        $user->update($validated);

        return back()->with(
            'success',
            'Profile updated successfully.'
        );
    }

    /**
     * Client deletes their own account.
     *
     * - needs the current password and the word DELETE
     * - upcoming open appointments are cancelled
     * - personal data is erased and the account can no longer log in
     * - past appointments stay (without personal data) so the clinic's
     *   income records remain correct
     */
    public function destroy(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'password' => ['required', 'current_password'],
            'confirmation' => ['required', 'in:DELETE'],
        ], [
            'password.required' => 'Please enter your password to delete your account.',
            'password.current_password' => 'The password you entered is incorrect.',
            'confirmation.required' => 'Please type DELETE to confirm.',
            'confirmation.in' => 'Please type the word DELETE exactly to confirm.',
        ]);

        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        DB::transaction(function () use ($user) {

            // cancel appointments that have not happened yet
            Appointment::where('user_id', $user->id)
                ->whereIn('status', ['pending', 'confirmed', 'rescheduled'])
                ->whereDate('appointment_date', '>=', today())
                ->update(['status' => 'cancelled']);

            // erase personal data (the email becomes free to register again)
            $user->forceFill([
                'first_name' => 'Deleted',
                'last_name' => 'User',
                'email' => 'deleted-' . $user->id . '-' . time() . '@deleted.invalid',
                'phone' => null,
                'date_of_birth' => null,
                'address' => null,
                'profile_picture' => null,
                'password' => Str::random(60),
                'remember_token' => null,
            ])->save();

            $user->delete();   // soft delete
        });

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'Your account has been deleted. We are sorry to see you go.');
    }
}
