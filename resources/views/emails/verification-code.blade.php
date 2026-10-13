<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin:0;padding:24px;background:#f7eef1;font-family:Arial,Helvetica,sans-serif;color:#2b2226;">
    <div style="max-width:480px;margin:0 auto;background:#ffffff;border-radius:16px;padding:32px;border:1px solid #f0d9e0;">

        <p style="margin:0 0 4px;font-size:12px;letter-spacing:2px;color:#b5657f;font-weight:bold;">
            M. CARES BEAUTY SERVICES
        </p>

        <h1 style="margin:0 0 16px;font-size:22px;">
            @if ($purpose === 'reset')
                Reset your password
            @else
                Verify your email address
            @endif
        </h1>

        <p style="margin:0 0 20px;font-size:15px;line-height:1.5;">
            @if ($purpose === 'reset')
                Use this code to choose a new password for your account:
            @else
                Use this code to finish creating your account:
            @endif
        </p>

        <div style="text-align:center;margin:0 0 20px;">
            <span style="display:inline-block;padding:14px 28px;border-radius:12px;background:#fbe9ef;color:#7a2e47;font-size:34px;font-weight:bold;letter-spacing:10px;">
                {{ $code }}
            </span>
        </div>

        <p style="margin:0 0 8px;font-size:14px;line-height:1.5;color:#5a4b51;">
            This code expires in {{ $minutes }} minutes. Never share it with anyone.
        </p>

        <p style="margin:0;font-size:13px;line-height:1.5;color:#8a7a80;">
            If you didn't request this, you can safely ignore this email.
        </p>

    </div>
</body>
</html>
