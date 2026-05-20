<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdminCredentialsMail;
use App\Models\User;
use App\Services\TwilioService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function __construct(private readonly TwilioService $twilioService)
    {
    }

    public function create(): View
    {
        $this->ensureAdmin();

        return view('admin.users.create-admin');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->ensureAdmin();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)],
            'phone_number' => ['required', 'string', 'max:20'],
        ]);

        $generatedPassword = Str::random(12);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'role' => 'admin',
            'password' => Hash::make($generatedPassword),
            'email_verified_at' => now(),
        ]);

        Mail::to($user->email)->send(new AdminCredentialsMail($user, $generatedPassword));

        $smsMessage = sprintf(
            "GovLog Sentinel admin account created. Email: %s | Password: %s | Please change it after first login.",
            $user->email,
            $generatedPassword
        );

        $this->twilioService->sendSms($user->phone_number, $smsMessage, false, false);

        return redirect()
            ->route('admin.dashboard')
            ->with('status', 'Admin account created and credentials sent by email and SMS.');
    }

    private function ensureAdmin(): void
    {
        abort_unless(Auth::user()?->isAdmin(), 403, 'Only administrators can create admin accounts.');
    }
}
