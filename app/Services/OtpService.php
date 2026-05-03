<?php

namespace App\Services;

use App\Models\EmailOtp;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;

class OtpService
{
    /**
     * Generate and send OTP to the given email
     */
    public function generateAndSend(string $email, string $type): EmailOtp
    {
        // Invalidate any existing OTPs for this email + type
        EmailOtp::where('email', $email)
            ->where('type', $type)
            ->where('is_verified', false)
            ->delete();

        // Generate a 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store the OTP (expires in 10 minutes)
        $otpRecord = EmailOtp::create([
            'email' => $email,
            'otp' => $otp,
            'type' => $type,
            'expires_at' => now()->addMinutes(10),
        ]);

        // Send OTP via email
        Mail::to($email)->send(new OtpMail($otp, $type));

        return $otpRecord;
    }

    /**
     * Verify the OTP for the given email
     */
    public function verify(string $email, string $otp, string $type): bool
    {
        $otpRecord = EmailOtp::where('email', $email)
            ->where('type', $type)
            ->where('is_verified', false)
            ->latest()
            ->first();

        if (!$otpRecord) {
            return false;
        }

        if ($otpRecord->isExpired()) {
            return false;
        }

        if ($otpRecord->otp !== $otp) {
            return false;
        }

        // Mark as verified
        $otpRecord->update(['is_verified' => true]);

        return true;
    }

    /**
     * Check if a verified OTP exists for this email + type
     */
    public function isVerified(string $email, string $type): bool
    {
        return EmailOtp::where('email', $email)
            ->where('type', $type)
            ->where('is_verified', true)
            ->where('expires_at', '>', now()->subMinutes(30)) // verified OTPs valid for 30 mins
            ->exists();
    }

    /**
     * Clean up used/expired OTPs
     */
    public function cleanup(string $email, string $type): void
    {
        EmailOtp::where('email', $email)
            ->where('type', $type)
            ->delete();
    }
}
