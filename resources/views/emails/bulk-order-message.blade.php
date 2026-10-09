<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Corporate Bulk Order Inquiry</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f4f5f7;
            margin: 0;
            padding: 24px;
            color: #1a1a1a;
            -webkit-font-smoothing: antialiased;
        }
        .email-container {
            max-width: 620px;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        .header {
            background-color: #0C0A09;
            padding: 30px 24px;
            text-align: center;
            border-bottom: 2px solid #C9A227;
        }
        .header h1 {
            color: #C9A227;
            font-family: Georgia, serif;
            font-size: 22px;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin: 0 0 6px;
        }
        .header p {
            color: #E8D5A3;
            font-size: 11px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin: 0;
        }
        .content {
            padding: 30px 24px;
        }
        .badge {
            display: inline-block;
            background: #fdf6e2;
            color: #926b00;
            border: 1px solid #e8d5a3;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 16px;
        }
        h2 {
            font-size: 18px;
            color: #111827;
            margin: 0 0 16px;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        .details-table th,
        .details-table td {
            padding: 12px 14px;
            text-align: left;
            font-size: 13px;
            border-bottom: 1px solid #edf2f7;
        }
        .details-table th {
            width: 35%;
            color: #718096;
            font-weight: 600;
            background: #fafafa;
        }
        .details-table td {
            color: #1a202c;
            font-weight: 500;
        }
        .message-box {
            background: #f8fafc;
            border-left: 3px solid #CC0000;
            padding: 16px;
            border-radius: 0 6px 6px 0;
            margin-bottom: 24px;
            font-size: 13.5px;
            line-height: 1.6;
            color: #2d3748;
            white-space: pre-line;
        }
        .footer {
            background: #fafafa;
            border-top: 1px solid #edf2f7;
            padding: 20px 24px;
            text-align: center;
            font-size: 11.5px;
            color: #718096;
        }
        .footer a {
            color: #CC0000;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>House of KNP</h1>
            <p>Bespoke Corporate Gifting &amp; Bulk Orders Desk</p>
        </div>

        <div class="content">
            <div class="badge">&#9733; Priority Corporate Lead</div>
            <h2>New Bulk Order Inquiry Received</h2>
            <p style="font-size: 13.5px; color: #4a5568; line-height: 1.5; margin-bottom: 20px;">
                A new corporate gifting and bulk order request has been submitted through the House of KNP storefront. Details are below:
            </p>

            <table class="details-table">
                <tr>
                    <th>Full Name</th>
                    <td><strong>{{ $inquiry['name'] ?? 'N/A' }}</strong></td>
                </tr>
                <tr>
                    <th>Company / Org</th>
                    <td>{{ $inquiry['company_name'] ?? 'Not specified' }}</td>
                </tr>
                <tr>
                    <th>Corporate Email</th>
                    <td><a href="mailto:{{ $inquiry['email'] ?? '' }}" style="color:#CC0000;">{{ $inquiry['email'] ?? 'N/A' }}</a></td>
                </tr>
                <tr>
                    <th>Phone / WhatsApp</th>
                    <td><a href="tel:{{ $inquiry['phone'] ?? '' }}" style="color:#1a202c; text-decoration:none;">{{ $inquiry['phone'] ?? 'N/A' }}</a></td>
                </tr>
                <tr>
                    <th>Product / Combo Box</th>
                    <td><strong>{{ $inquiry['product_interest'] ?? 'General Curation' }}</strong></td>
                </tr>
                <tr>
                    <th>Received At</th>
                    <td>{{ now()->timezone('Asia/Kolkata')->format('d M Y, h:i A') }} IST</td>
                </tr>
            </table>

            @if(!empty($inquiry['message']))
            <h3 style="font-size: 14px; margin: 0 0 8px; color: #111827; text-transform: uppercase; letter-spacing: 0.5px;">Client Requirements &amp; Notes:</h3>
            <div class="message-box">
                {{ $inquiry['message'] }}
            </div>
            @endif

            <p style="font-size: 12px; color: #718096; margin-top: 20px;">
                <em>Action item: Please respond to this corporate lead within 2 to 4 business hours to maintain our concierge standard.</em>
            </p>
        </div>

        <div class="footer">
            <p style="margin: 0 0 6px;">This is an automated notification from <a href="{{ url('/') }}">House of KNP Storefront</a>.</p>
            <p style="margin: 0;">&copy; {{ date('Y') }} House of KNP. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
