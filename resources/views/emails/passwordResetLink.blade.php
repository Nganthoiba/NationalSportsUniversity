<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Password Reset - National Sport's University</title>
</head>

<body
    style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f1f5f9; margin: 0; padding: 40px;">

    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0"
                    style="background-color: #ffffff; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); padding: 30px;">

                    {{-- Header --}}
                    <tr>
                        <td align="center" style="padding-bottom: 20px;">
                            <h1 style="color: #1e293b; font-size: 24px; margin: 0;">National Sport's University</h1>
                            <p style="color: #64748b; font-size: 14px; margin-top: 5px;">Empowering Sports Excellence
                            </p>
                        </td>
                    </tr>

                    {{-- Message --}}
                    <tr>
                        <td style="color: #334155; font-size: 16px;">
                            <p>Dear {{ $name }},</p>

                            <p>We received a request to reset your password for your National Sport's University
                                account.</p>

                            <p>To proceed, please click the button below. This secure link will expire in
                                <strong>{{ env('PASSWORD_RESET_LINK_EXPIRE') }} minutes</strong> for your safety.
                            </p>

                            {{-- Reset Button --}}
                            <p style="text-align: center; margin: 30px 0;">
                                <a href="{{ $resetLink }}"
                                    style="background-color: #2563eb; color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: 500;">
                                    Reset Your Password
                                </a>
                            </p>

                            <p>If you didn’t request this, you can ignore this email and your current password will
                                remain unchanged.</p>

                            {{-- Fallback link --}}
                            <p style="font-size: 13px; color: #94a3b8; margin-top: 30px;">
                                If the button doesn't work, copy and paste the following URL into your browser:
                            </p>
                            <p style="font-size: 13px; word-break: break-all; color: #475569;">{{ $resetLink }}</p>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="padding-top: 30px; border-top: 1px solid #e2e8f0; text-align: center;">
                            <p style="font-size: 12px; color: #94a3b8;">
                                This message was sent from the <br>
                                <strong>National Sport's University</strong>, Imphal, Manipur.<br>
                                &copy; {{ date('Y') }} National Sport's University. All rights reserved.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>

</html>
