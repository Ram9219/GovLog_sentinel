<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    protected OtpService $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Step 1: Validate registration data and send OTP to email.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Store registration data in session
        $request->session()->put('registration_data', [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => 'viewer',
            'password' => $request->password,
        ]);

        // Generate and send OTP
        $this->otpService->generateAndSend($request->email, 'registration');

        return redirect()->route('register.verify-otp')
            ->with('status', 'A 6-digit OTP has been sent to ' . $request->email);
    }

    /**
     * Show OTP verification form for registration.
     */
    public function showVerifyOtp(Request $request): View|RedirectResponse
    {
        if (!$request->session()->has('registration_data')) {
            return redirect()->route('register');
        }

        $email = $request->session()->get('registration_data.email');
        return view('auth.verify-registration-otp', compact('email'));
    }

    /**
     * Step 2: Verify OTP and create the user account.
     */
    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $registrationData = $request->session()->get('registration_data');

        if (!$registrationData) {
            return redirect()->route('register')
                ->withErrors(['email' => 'Registration session expired. Please try again.']);
        }

        $email = $registrationData['email'];

        // Verify OTP
        if (!$this->otpService->verify($email, $request->otp, 'registration')) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP. Please try again.']);
        }

        // OTP verified — create the user
        $user = User::create([
            'name' => $registrationData['name'],
            'email' => $email,
            'phone' => $registrationData['phone'],
            'role' => $registrationData['role'],
            'password' => Hash::make($registrationData['password']),
            'email_verified_at' => now(), // Mark email as verified since OTP confirmed it
        ]);

        // Cleanup
        $this->otpService->cleanup($email, 'registration');
        $request->session()->forget('registration_data');

        event(new Registered($user));
        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }

    /**
     * Resend OTP for registration.
     */
    public function resendOtp(Request $request): RedirectResponse
    {
        $registrationData = $request->session()->get('registration_data');

        if (!$registrationData) {
            return redirect()->route('register');
        }

        $this->otpService->generateAndSend($registrationData['email'], 'registration');

        return back()->with('status', 'A new OTP has been sent to ' . $registrationData['email']);
    }
}
