<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Account Created</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f3f4f6;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #f3f4f6; padding: 40px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="520" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.07); overflow: hidden;">
                    <tr>
                        <td style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); padding: 30px 40px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 24px; font-weight: 700;">GovLog Sentinel</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 40px;">
                            <h2 style="color: #1f2937; margin: 0 0 12px 0; font-size: 20px;">Admin Account Created</h2>
                            <p style="color: #6b7280; margin: 0 0 24px 0; font-size: 14px; line-height: 1.6;">
                                Your administrator account has been created successfully.
                            </p>

                            <div style="background-color: #eff6ff; border: 1px solid #bfdbfe; border-radius: 10px; padding: 20px; margin: 0 0 24px 0;">
                                <p style="margin: 0 0 8px 0; color: #1e3a8a; font-size: 14px;"><strong>Name:</strong> {{ $user->name }}</p>
                                <p style="margin: 0 0 8px 0; color: #1e3a8a; font-size: 14px;"><strong>Email:</strong> {{ $user->email }}</p>
                                <p style="margin: 0; color: #1e3a8a; font-size: 14px;"><strong>Role:</strong> Admin</p>
                            </div>

                            <div style="background-color: #f8f5ff; border: 2px dashed #8b5cf6; border-radius: 12px; padding: 24px; text-align: center; margin: 0 0 24px 0;">
                                <p style="color: #6b7280; margin: 0 0 8px 0; font-size: 12px; text-transform: uppercase; letter-spacing: 2px; font-weight: 600;">Temporary Password</p>
                                <p style="color: #7c3aed; margin: 0; font-size: 28px; font-weight: 800; letter-spacing: 4px; font-family: 'Courier New', monospace;">{{ $temporaryPassword }}</p>
                            </div>

                            <p style="color: #ef4444; margin: 0 0 24px 0; font-size: 13px; font-weight: 500;">
                                Change this password after your first sign in.
                            </p>

                            <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 24px 0;">

                            <p style="color: #9ca3af; margin: 0; font-size: 12px; line-height: 1.5;">
                                If you did not expect this account, please contact the system administrator immediately.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
