<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\VerificationCodes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Throwable;

class RegisterController extends Controller
{
    private const SESSION_KEY = 'register_email';

    public function create()
    {
        return view('auth.register');
    }

    /**
     * Step 1: validate the form and email a code.
     * The account is NOT created yet - it is only created once the
     * person proves they own the email address (see verify()).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],

            // Exactly 11 numbers
            'phone' => ['required', 'digits:11'],

            // Must be at least 18 years old
            'date_of_birth' => [
                'required',
                'date',
                'before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
            ],

            'address' => ['nullable', 'string', 'max:1000'],

            // Uppercase + lowercase + number + special character
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).+$/',
            ],

            // Data Privacy: the box must be ticked to register
            'terms' => ['accepted'],
        ], [

            'terms.accepted' =>
                'You must agree to the Terms and Conditions and Data Privacy Notice to register.',

            'phone.digits' =>
                'Phone number must contain exactly 11 digits.',

            'date_of_birth.required' =>
                'Date of birth is required.',

            'date_of_birth.before_or_equal' =>
                'You must be at least 18 years old to register.',

            'password.regex' =>
                'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',

        ]);

        // 'terms' is only a checkbox - it is not a column on the users table.
        unset($validated['terms']);

        // Public registration can only create a client account.
        $validated['role'] = 'client';

        // Keep the details (with the password already hashed) until the
        // email is verified.
        $validated['password'] = Hash::make($validated['password']);

        try {
            VerificationCodes::issue($validated['email'], VerificationCodes::PURPOSE_REGISTER, $validated);
        } catch (Throwable $e) {
            report($e);

            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors(['email' => 'We could not send the verification email. Please check the address and try again.']);
        }

        $request->session()->put(self::SESSION_KEY, $validated['email']);

        return redirect()->route('register.verify')
            ->with('success', 'We sent a 6-digit code to your email. Enter it below to finish creating your account.');
    }

    /** Step 2: page where the code is typed in. */
    public function showVerify(Request $request)
    {
        $email = $request->session()->get(self::SESSION_KEY);

        if (! $email) {
            return redirect()->route('register');
        }

        return view('auth.verify-email', [
            'maskedEmail' => VerificationCodes::mask($email),
        ]);
    }

    /** Step 2 (submit): check the code, then create the account. */
    public function verify(Request $request)
    {
        $email = $request->session()->get(self::SESSION_KEY);

        if (! $email) {
            return redirect()->route('register');
        }

        $request->validate([
            'code' => ['required', 'digits:6'],
        ], [
            'code.digits' => 'The code must be exactly 6 digits.',
        ]);

        $result = VerificationCodes::verify($email, VerificationCodes::PURPOSE_REGISTER, $request->input('code'));

        if (! $result['ok']) {
            return back()->withErrors(['code' => $result['error']]);
        }

        $data = $result['payload'];

        // Someone could have taken this email while the code was pending.
        if (User::whereRaw('LOWER(email) = ?', [VerificationCodes::normalize($data['email'])])->exists()) {
            VerificationCodes::forget($email, VerificationCodes::PURPOSE_REGISTER);
            $request->session()->forget(self::SESSION_KEY);

            return redirect()->route('register')
                ->withErrors(['email' => 'That email address is already registered.']);
        }

        $user = User::create($data);
        $user->forceFill(['email_verified_at' => now()])->save();

        VerificationCodes::forget($email, VerificationCodes::PURPOSE_REGISTER);
        $request->session()->forget(self::SESSION_KEY);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home')
            ->with('success', 'Welcome to M. Care! Your email is verified and your account has been created.');
    }

    /** Send a new code for the pending registration. */
    public function resend(Request $request)
    {
        $email = $request->session()->get(self::SESSION_KEY);

        if (! $email) {
            return redirect()->route('register');
        }

        $wait = VerificationCodes::secondsUntilResend($email, VerificationCodes::PURPOSE_REGISTER);

        if ($wait > 0) {
            return back()->withErrors(['code' => "Please wait {$wait} seconds before requesting another code."]);
        }

        try {
            $sent = VerificationCodes::resend($email, VerificationCodes::PURPOSE_REGISTER);
        } catch (Throwable $e) {
            report($e);

            return back()->withErrors(['code' => 'We could not send the email right now. Please try again in a moment.']);
        }

        if (! $sent) {
            $request->session()->forget(self::SESSION_KEY);

            return redirect()->route('register')
                ->withErrors(['email' => 'Your verification request expired. Please fill in the form again.']);
        }

        return back()->with('success', 'A new code has been sent to your email.');
    }
}
