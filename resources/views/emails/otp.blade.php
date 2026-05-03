<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f3f4f6;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #f3f4f6; padding: 40px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="480" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.07); overflow: hidden;">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px 40px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 24px; font-weight: 700;">🛡️ GovLog Sentinel</h1>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 40px;">
                            <h2 style="color: #1f2937; margin: 0 0 8px 0; font-size: 20px;">
                                @if($type === 'registration')
                                    Email Verification
                                @else
                                    Password Reset
                                @endif
                            </h2>
                            <p style="color: #6b7280; margin: 0 0 24px 0; font-size: 14px; line-height: 1.6;">
                                @if($type === 'registration')
                                    Thank you for registering with GovLog Sentinel. Please use the following OTP to verify your email address.
                                @else
                                    We received a request to reset your password. Please use the following OTP to proceed.
                                @endif
                            </p>

                            <!-- OTP Box -->
                            <div style="background-color: #f8f5ff; border: 2px dashed #8b5cf6; border-radius: 12px; padding: 24px; text-align: center; margin: 0 0 24px 0;">
                                <p style="color: #6b7280; margin: 0 0 8px 0; font-size: 12px; text-transform: uppercase; letter-spacing: 2px; font-weight: 600;">Your OTP Code</p>
                                <p style="color: #7c3aed; margin: 0; font-size: 36px; font-weight: 800; letter-spacing: 8px; font-family: 'Courier New', monospace;">{{ $otp }}</p>
                            </div>

                            <p style="color: #ef4444; margin: 0 0 24px 0; font-size: 13px; font-weight: 500;">
                                ⏱ This OTP expires in <strong>10 minutes</strong>. Do not share it with anyone.
                            </p>

                            <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 24px 0;">

                            <p style="color: #9ca3af; margin: 0; font-size: 12px; line-height: 1.5;">
                                If you did not request this, please ignore this email. For security concerns, contact our support team.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f9fafb; padding: 20px 40px; text-align: center; border-top: 1px solid #e5e7eb;">
                            <p style="color: #9ca3af; margin: 0; font-size: 11px;">
                                &copy; {{ date('Y') }} GovLog Sentinel. All rights reserved.<br>
                                AICTE Compliant E-Governance Solution
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
