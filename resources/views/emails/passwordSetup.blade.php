<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Account Created - National Sports University</title>
</head>

<body
    style="margin: 0; padding: 0; background-color: #f6f9fc; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #333;">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table width="600" cellpadding="0" cellspacing="0"
                    style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #004aad; padding: 24px; text-align: center; color: #ffffff;">
                            <h1 style="margin: 0; font-size: 24px;">National Sports University</h1>
                            <p style="margin: 0; font-size: 14px;">Manipur, India</p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 32px;">
                            <h2 style="margin-top: 0; color: #004aad;">Dear {{ $user->full_name }},</h2>

                            <p>We’re delighted to inform you that your account has been successfully created at the
                                <strong>National Sports University Staff Portal</strong>.
                            </p>

                            <p>Your registered email: <strong>{{ $user->email }}</strong></p>

                            <p>To complete your registration and access the portal, please set your password using the
                                link below:</p>

                            <!-- Action Button -->
                            <div style="text-align: center; margin: 30px 0;">
                                <a href="{{ $passwordSetupUrl }}"
                                    style="display: inline-block; padding: 12px 24px; background-color: #004aad; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: bold;">
                                    Complete Your Registration
                                </a>
                            </div>

                            <!-- Fallback link -->
                            <p style="font-size: 14px; color: #555;">If the button above does not work, you can copy and
                                paste the following link into your browser:</p>
                            <p style="word-break: break-all;"><a href="{{ $passwordSetupUrl }}"
                                    style="color: #004aad;">{{ $passwordSetupUrl }}</a></p>

                            <p>If you did not expect this message, please disregard it.</p>

                            <p style="margin-top: 40px;">Warm regards,<br><strong>NSU Admin Team</strong></p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td
                            style="background-color: #f0f4f8; text-align: center; font-size: 12px; color: #888888; padding: 16px;">
                            &copy; {{ now()->year }} National Sports University. All rights reserved.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
