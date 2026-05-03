<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    protected OtpService $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    /**
     * Display the forgot password view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Send OTP for password reset.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Check if user exists
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'We could not find an account with that email address.']);
        }

        // Generate and send OTP
        $this->otpService->generateAndSend($request->email, 'password_reset');

        // Store email in session for the next step
        $request->session()->put('password_reset_email', $request->email);

        return redirect()->route('password.verify-otp')
            ->with('status', 'A 6-digit OTP has been sent to ' . $request->email);
    }
}
