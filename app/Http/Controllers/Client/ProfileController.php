<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

            'phone' => [
                'required',
                'string',
                'max:30'
            ],

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
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048'
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
}