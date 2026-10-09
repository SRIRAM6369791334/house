@php
    $brandName = config('mail.from.name', 'House of KNP');
@endphp
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contact Message Received</title>
</head>
<body style="margin:0; padding:0; background:#f7efdf; font-family:Arial, Helvetica, sans-serif; color:#222;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f7efdf; margin:0; padding:0;">
        <tr>
            <td align="center">
                <table role="presentation" width="750" cellspacing="0" cellpadding="0" style="max-width:750px; width:100%; background:#fff;">
                    <tr>
                        <td align="center" style="background:linear-gradient(90deg,#9d650e,#c89422); padding:38px 20px 54px;">
                            <div style="color:#fff; font-size:34px; font-weight:900; letter-spacing:2px; text-transform:uppercase;">
                                {{ $brandName }}
                            </div>
                            <div style="color:#fff; font-size:17px; font-weight:800; margin-top:18px;">
                                New Contact Form Message
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:50px 38px 42px;">
                            <div style="text-align:center;">
                                <div style="background:#d59b21; border-radius:50%; color:#fff; display:inline-block; font-size:28px; font-weight:900; height:50px; line-height:50px; width:50px;">!</div>
                                <h1 style="font-size:28px; line-height:1.25; margin:28px 0 14px;">Contact Message Received</h1>
                                <p style="color:#666; font-size:18px; line-height:1.5; margin:0 0 48px;">
                                    A customer has submitted a message from the contact form.
                                </p>
                            </div>

                            <h2 style="font-size:22px; margin:0 0 22px;">Customer Details</h2>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse;">
                                <tr style="background:#fbf4e4;">
                                    <td style="color:#8b640d; font-weight:800; padding:20px 18px; width:30%;">Name</td>
                                    <td style="padding:20px 18px;">{{ $contact['name'] }}</td>
                                </tr>
                                <tr>
                                    <td style="color:#8b640d; font-weight:800; padding:20px 18px;">Email</td>
                                    <td style="padding:20px 18px;">
                                        @if(!empty($contact['email']))
                                            <a href="mailto:{{ $contact['email'] }}" style="color:#c58b15; font-weight:800; text-decoration:none;">{{ $contact['email'] }}</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr style="background:#fbf4e4;">
                                    <td style="color:#8b640d; font-weight:800; padding:20px 18px;">Phone</td>
                                    <td style="padding:20px 18px;">{{ $contact['phone'] ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td style="color:#8b640d; font-weight:800; padding:20px 18px;">Subject</td>
                                    <td style="padding:20px 18px;">{{ $contact['subject'] ?? '-' }}</td>
                                </tr>
                                <tr style="background:#fbf4e4;">
                                    <td style="color:#8b640d; font-weight:800; padding:20px 18px; vertical-align:top;">Message</td>
                                    <td style="line-height:1.7; padding:20px 18px; white-space:pre-wrap;">{{ $contact['message'] }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
