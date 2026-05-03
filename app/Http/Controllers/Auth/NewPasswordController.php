<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    protected OtpService $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    /**
     * Show OTP verification form for password reset.
     */
    public function showVerifyOtp(Request $request): View|RedirectResponse
    {
        if (!$request->session()->has('password_reset_email')) {
            return redirect()->route('password.request');
        }

        $email = $request->session()->get('password_reset_email');
        return view('auth.verify-reset-otp', compact('email'));
    }

    /**
     * Verify OTP and show the new password form.
     */
    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $email = $request->session()->get('password_reset_email');

        if (!$email) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Session expired. Please try again.']);
        }

        // Verify OTP
        if (!$this->otpService->verify($email, $request->otp, 'password_reset')) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP. Please try again.']);
        }

        // OTP verified — allow password reset
        $request->session()->put('password_reset_verified', true);

        return redirect()->route('password.reset-form');
    }

    /**
     * Show the new password form (after OTP verification).
     */
    public function create(Request $request): View|RedirectResponse
    {
        $email = $request->session()->get('password_reset_email');
        $verified = $request->session()->get('password_reset_verified', false);

        if (!$email || !$verified) {
            return redirect()->route('password.request');
        }

        return view('auth.reset-password', compact('email'));
    }

    /**
     * Handle the new password submission.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $email = $request->session()->get('password_reset_email');
        $verified = $request->session()->get('password_reset_verified', false);

        if (!$email || !$verified) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Session expired. Please try again.']);
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'User not found.']);
        }

        // Reset the password
        $user->forceFill([
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(60),
        ])->save();

        event(new PasswordReset($user));

        // Cleanup session
        $this->otpService->cleanup($email, 'password_reset');
        $request->session()->forget(['password_reset_email', 'password_reset_verified']);

        return redirect()->route('login')
            ->with('status', 'Your password has been reset successfully! Please log in.');
    }

    /**
     * Resend OTP for password reset.
     */
    public function resendOtp(Request $request): RedirectResponse
    {
        $email = $request->session()->get('password_reset_email');

        if (!$email) {
            return redirect()->route('password.request');
        }

        $this->otpService->generateAndSend($email, 'password_reset');

        return back()->with('status', 'A new OTP has been sent to ' . $email);
    }
}
