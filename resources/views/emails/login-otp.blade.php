<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Verification Code</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background-color: #f4f6f9; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .card { background: #ffffff; border-radius: 16px; padding: 40px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
        .logo { text-align: center; margin-bottom: 24px; }
        .logo img { height: 60px; }
        .title { text-align: center; font-size: 24px; font-weight: 700; color: #1e3a5f; margin-bottom: 8px; }
        .subtitle { text-align: center; font-size: 14px; color: #64748b; margin-bottom: 32px; }
        .otp-box { background: #f0f7ff; border: 2px dashed #3b82f6; border-radius: 12px; padding: 24px; text-align: center; margin: 24px 0; }
        .otp-code { font-size: 42px; font-weight: 800; letter-spacing: 12px; color: #1d4ed8; font-family: 'Courier New', monospace; }
        .expiry { text-align: center; font-size: 13px; color: #94a3b8; margin-top: 16px; }
        .footer { text-align: center; font-size: 12px; color: #94a3b8; margin-top: 32px; line-height: 1.6; }
        .warning { background: #fef2f2; border: 1px solid #fca5a5; border-radius: 8px; padding: 12px 16px; font-size: 12px; color: #991b1b; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="logo">
                <img src="{{ asset('img/NVG_LOGO_org.png') }}" alt="NVG Prime Movers">
            </div>
            <div class="title">Verify Your Login</div>
            <div class="subtitle">Hello, <strong>{{ $userName }}</strong>! Enter this code to complete your login.</div>

            <div class="otp-box">
                <div class="otp-code">{{ $otp }}</div>
            </div>

            <div class="expiry">This code expires in 5 minutes.</div>

            <div class="warning">
                If you did not attempt to log in, please ignore this email. Do not share this code with anyone.
            </div>

            <div class="footer">
                &copy; {{ date('Y') }} NVG Prime Movers. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>
