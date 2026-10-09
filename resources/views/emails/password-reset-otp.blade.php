<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Password Reset OTP</title>
</head>
<body style="margin:0;background:#f4f4f4;font-family:Arial,Helvetica,sans-serif;color:#252525;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f4f4f4;padding:30px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:600px;background:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 8px 28px rgba(0,0,0,.08);">
                    <tr>
                        <td align="center" style="padding:24px;background:#ffffff;border-bottom:4px solid #cc0000;">
                            <img src="{{ $message->embed(public_path('images/logo/logoo.png')) }}" alt="House of KNP" style="display:block;max-width:170px;max-height:82px;width:auto;height:auto;">
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:34px 38px;">
                            <h1 style="margin:0 0 14px;font-size:24px;color:#111111;">Reset your password</h1>
                            <p style="margin:0 0 22px;font-size:15px;line-height:1.7;color:#555555;">Use the verification code below to continue changing your House of KNP account password.</p>
                            <div style="margin:0 auto 22px;padding:18px;text-align:center;background:#fff5f5;border:1px solid #f0caca;border-radius:7px;font-size:32px;font-weight:700;letter-spacing:9px;color:#cc0000;">{{ $otp }}</div>
                            <p style="margin:0 0 10px;font-size:14px;line-height:1.6;color:#555555;">This OTP expires in {{ $expiresInMinutes }} minutes and can be used only once.</p>
                            <p style="margin:0;font-size:14px;line-height:1.6;color:#777777;">If you did not request a password reset, you can safely ignore this email.</p>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding:18px;background:#111111;color:#ffffff;font-size:12px;letter-spacing:.4px;">House of KNP &mdash; Ignite Your Presence</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
