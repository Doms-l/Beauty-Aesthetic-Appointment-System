<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\VerificationCodes;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Throwable;

class ForgotPasswordController extends Controller
{
    private const SESSION_KEY = 'reset_email';

    /** Step 1: ask for the email address. */
    public function create()
    {
        return view('auth.forgot-password');
    }

    /** Step 1 (submit): email the code. */
    public function send(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $email = VerificationCodes::normalize($data['email']);

        // Same answer whether or not the email has an account, so nobody
        // can use this form to find out who is registered.
        if ($this->findUser($email)
            && VerificationCodes::secondsUntilResend($email, VerificationCodes::PURPOSE_RESET) === 0
        ) {
            try {
                VerificationCodes::issue($email, VerificationCodes::PURPOSE_RESET);
            } catch (Throwable $e) {
                report($e);

                return back()
                    ->withInput()
                    ->withErrors(['email' => 'We could not send the email right now. Please try again in a moment.']);
            }
        }

        $request->session()->put(self::SESSION_KEY, $email);

        return redirect()->route('password.reset')
            ->with('success', 'If that email belongs to an account, we sent a 6-digit code to it. Check your Gmail inbox.');
    }

    /** Step 2: enter the code and a new password. */
    public function edit(Request $request)
    {
        $email = $request->session()->get(self::SESSION_KEY);

        if (! $email) {
            return redirect()->route('password.request');
        }

        return view('auth.reset-password', [
            'maskedEmail' => VerificationCodes::mask($email),
        ]);
    }

    /** Step 2 (submit): check the code and save the new password. */
    public function update(Request $request)
    {
        $email = $request->session()->get(self::SESSION_KEY);

        if (! $email) {
            return redirect()->route('password.request');
        }

        $request->validate([
            'code' => ['required', 'digits:6'],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).+$/',
            ],
        ], [
            'code.digits' => 'The code must be exactly 6 digits.',
            'password.regex' =>
                'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
        ]);

        $result = VerificationCodes::verify($email, VerificationCodes::PURPOSE_RESET, $request->input('code'));

        $user = $result['ok'] ? $this->findUser($email) : null;

        if (! $result['ok'] || ! $user) {
            return back()->withErrors([
                'code' => $result['error'] ?? 'That code is invalid or has expired. Please request a new one.',
            ]);
        }

        $user->password = $request->input('password');
        $user->remember_token = Str::random(60); // signs out "remember me" sessions
        $user->save();

        VerificationCodes::forget($email, VerificationCodes::PURPOSE_RESET);
        $request->session()->forget(self::SESSION_KEY);

        return redirect()->route('login')
            ->with('success', 'Your password has been changed. You can now log in.');
    }

    /** Send a new code. */
    public function resend(Request $request)
    {
        $email = $request->session()->get(self::SESSION_KEY);

        if (! $email) {
            return redirect()->route('password.request');
        }

        $wait = VerificationCodes::secondsUntilResend($email, VerificationCodes::PURPOSE_RESET);

        if ($wait > 0) {
            return back()->withErrors(['code' => "Please wait {$wait} seconds before requesting another code."]);
        }

        if ($this->findUser($email)) {
            try {
                VerificationCodes::issue($email, VerificationCodes::PURPOSE_RESET);
            } catch (Throwable $e) {
                report($e);

                return back()->withErrors(['code' => 'We could not send the email right now. Please try again in a moment.']);
            }
        }

        return back()->with('success', 'A new code has been sent if that email belongs to an account.');
    }

    private function findUser(string $email): ?User
    {
        return User::whereRaw('LOWER(email) = ?', [$email])->first();
    }
}
