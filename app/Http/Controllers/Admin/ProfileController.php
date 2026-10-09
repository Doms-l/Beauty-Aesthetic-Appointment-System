<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        return view('admin.profile', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email,' . $user->id,
            ],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:1000'],
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
            'Admin profile updated successfully.'
        );
    }
}