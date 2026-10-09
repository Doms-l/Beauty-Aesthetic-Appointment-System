<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    public function index()
    {
        $staff = Staff::with('user')->latest()->paginate(12);

        return view('admin.staff', compact('staff'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['required', 'digits:11'],
            'position' => ['required', 'string', 'max:100'],
            'specialization' => ['nullable', 'string', 'max:150'],
            'profile_picture' => ['nullable', 'file', 'mimetypes:image/jpeg,image/png,image/webp', 'max:5120'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'phone.required' => 'Phone number is required.',
            'phone.digits' => 'Phone number must contain exactly 11 digits (numbers only).',
        ]);

        $picture = $request->hasFile('profile_picture')
            ? $request->file('profile_picture')->store('profile-pictures', 'public')
            : null;

        DB::transaction(function () use ($validated, $picture) {
            $user = User::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'profile_picture' => $picture,
                'password' => $validated['password'],
                'role' => 'staff',
            ]);

            Staff::create([
                'user_id' => $user->id,
                'position' => $validated['position'],
                'specialization' => $validated['specialization'] ?? null,
                'is_available' => true,
            ]);
        });

        return back()->with('success', 'Staff account created successfully.');
    }

    public function update(Request $request, Staff $staff)
    {
        $user = $staff->user;

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['required', 'digits:11'],
            'position' => ['required', 'string', 'max:100'],
            'specialization' => ['nullable', 'string', 'max:150'],
            'profile_picture' => ['nullable', 'file', 'mimetypes:image/jpeg,image/png,image/webp', 'max:5120'],
            // leave empty to keep the current password
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'is_available' => ['nullable', 'boolean'],
        ], [
            'phone.required' => 'Phone number is required.',
            'phone.digits' => 'Phone number must contain exactly 11 digits (numbers only).',
        ]);

        DB::transaction(function () use ($request, $validated, $staff, $user) {

            $userData = [
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
            ];

            if (!empty($validated['password'])) {
                $userData['password'] = $validated['password'];
            }

            // A new photo replaces the old one
            if ($request->hasFile('profile_picture')) {

                if ($user->profile_picture) {
                    Storage::disk('public')->delete($user->profile_picture);
                }

                $userData['profile_picture'] = $request->file('profile_picture')
                    ->store('profile-pictures', 'public');
            }

            $user->update($userData);

            $staff->update([
                'position' => $validated['position'],
                'specialization' => $validated['specialization'] ?? null,
                'is_available' => $request->boolean('is_available'),
            ]);
        });

        return back()->with('success', 'Staff member updated successfully.');
    }

    /**
     * Remove a staff member (for example when they leave the clinic).
     *
     * Their login account is deleted. Appointments they were assigned to
     * are kept but become "Unassigned" so the admin can pick someone else.
     */
    public function destroy(Staff $staff)
    {
        $user = $staff->user;

        if ($user && $user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        DB::transaction(function () use ($staff, $user) {
            // deleting the user also deletes the staff row (cascade);
            // appointments.staff_id is set to NULL automatically
            if ($user) {
                $user->delete();
            } else {
                $staff->delete();
            }
        });

        return back()->with('success', 'Staff member removed.');
    }
}
