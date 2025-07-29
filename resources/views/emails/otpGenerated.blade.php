<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>OTP for Password Reset</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            color: #333;
            padding: 20px;
        }

        .email-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 6px;
            max-width: 600px;
            margin: 0 auto;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .otp-code {
            font-size: 28px;
            font-weight: bold;
            color: #2c3e50;
            letter-spacing: 3px;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #777;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <h2>Hello {{ $user_name }},</h2>

        <p>We received a request to reset your password. Use the following One-Time Password (OTP) to proceed:</p>

        <p class="otp-code">{{ $otp['otp_val'] }}</p>

        <p>This OTP is valid for the next <strong>5 minutes</strong>. Please do not share this OTP with anyone for
            security reasons.</p>

        <p>If you did not request a password reset, you can safely ignore this email.</p>

        {{-- Optional Debug/Tracking Info --}}
        <p style="font-size: 11px; color: #aaa;">
            OTP ID: {{ $otp['otp_id'] }}
        </p>

        <div class="footer">
            &copy; {{ date('Y') }} National Sport's University. All rights reserved.
        </div>
    </div>
</body>

</html>
