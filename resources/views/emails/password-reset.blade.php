<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reset Your Password</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f1f5f9; margin: 0; padding: 40px 20px; }
        .container { max-width: 560px; margin: 0 auto; background: #1e293b; border-radius: 16px; overflow: hidden; }
        .header { background: linear-gradient(135deg, #4338ca, #6366f1); padding: 40px; text-align: center; }
        .header h1 { color: white; font-size: 22px; margin: 0; font-weight: 700; }
        .body { padding: 40px; }
        .body p { color: #cbd5e1; font-size: 15px; line-height: 1.7; margin: 0 0 16px; }
        .btn { display: inline-block; background: #6366f1; color: white !important; text-decoration: none;
               font-weight: 700; padding: 14px 32px; border-radius: 10px; font-size: 15px; margin: 8px 0 24px; }
        .warning { background: #0f172a; border-radius: 10px; padding: 16px; color: #94a3b8; font-size: 13px; line-height: 1.6; }
        .footer { padding: 20px 40px; border-top: 1px solid #334155; text-align: center; color: #475569; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 Reset Your Password</h1>
        </div>
        <div class="body">
            <p>Hi <strong style="color:#e2e8f0">{{ $user->name }}</strong>,</p>
            <p>We received a request to reset the password for your Profiles CRUD Auth account. Click the button below to set a new password:</p>
            <div style="text-align:center">
                <a href="{{ $resetUrl }}" class="btn">Reset My Password</a>
            </div>
            <p>This link will expire in <strong style="color:#e2e8f0">60 minutes</strong>.</p>
            <div class="warning">
                <strong style="color:#e2e8f0">⚠️ Didn't request this?</strong><br>
                If you didn't request a password reset, no action is needed. Your password remains unchanged.
                If you're concerned, please contact your administrator.
            </div>
            <p style="margin-top:20px">Or copy and paste this URL into your browser:<br>
                <span style="color:#818cf8; font-size:12px; word-break:break-all;">{{ $resetUrl }}</span>
            </p>
        </div>
        <div class="footer">
            Profiles CRUD Auth &copy; {{ date('Y') }} — This is an automated message, please do not reply.
        </div>
    </div>
</body>
</html>
