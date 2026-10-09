<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inquiry Received — House of KNP Corporate Gifting</title>
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
            padding: 32px 24px;
            text-align: center;
            border-bottom: 2px solid #C9A227;
        }
        .header h1 {
            color: #C9A227;
            font-family: Georgia, serif;
            font-size: 24px;
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
            padding: 32px 24px;
        }
        .badge {
            display: inline-block;
            background: #fdf6e2;
            color: #926b00;
            border: 1px solid #e8d5a3;
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 18px;
        }
        h2 {
            font-size: 20px;
            color: #111827;
            margin: 0 0 14px;
            font-family: Georgia, serif;
        }
        p {
            font-size: 14px;
            color: #4a5568;
            line-height: 1.6;
            margin: 0 0 16px;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0 24px;
            border: 1px solid #edf2f7;
            border-radius: 6px;
            overflow: hidden;
        }
        .details-table th,
        .details-table td {
            padding: 12px 16px;
            text-align: left;
            font-size: 13.5px;
            border-bottom: 1px solid #edf2f7;
        }
        .details-table th {
            width: 38%;
            color: #718096;
            font-weight: 600;
            background: #fafafa;
        }
        .details-table td {
            color: #1a202c;
            font-weight: 500;
        }
        .highlight-box {
            background: #faf9f6;
            border-left: 4px solid #C9A227;
            padding: 18px 20px;
            border-radius: 0 8px 8px 0;
            margin: 24px 0;
        }
        .highlight-box h4 {
            margin: 0 0 6px;
            color: #926b00;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .highlight-box p {
            margin: 0;
            font-size: 13px;
            color: #4a5568;
            line-height: 1.5;
        }
        .cta-section {
            text-align: center;
            padding: 16px 0;
        }
        .btn-wa {
            display: inline-block;
            background: #25D366;
            color: #ffffff !important;
            padding: 12px 24px;
            border-radius: 25px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        .footer {
            background: #fafafa;
            border-top: 1px solid #edf2f7;
            padding: 24px;
            text-align: center;
            font-size: 12px;
            color: #718096;
            line-height: 1.6;
        }
        .footer a {
            color: #CC0000;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>House of KNP</h1>
            <p>Bespoke Corporate Gifting &amp; Bulk Orders Concierge</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="badge">&#10003; Inquiry Received</div>
            <h2>Dear {{ $inquiry['name'] ?? 'Valued Client' }},</h2>
            <p>
                Thank you for considering <strong>House of KNP</strong> for your corporate gifting and bulk order requirements. We have received your inquiry on behalf of <strong>{{ $inquiry['company_name'] ?? 'your organization' }}</strong>.
            </p>

            <table class="details-table">
                <tr>
                    <th>Selected Product / Category</th>
                    <td><strong>{{ $inquiry['product_interest'] ?? 'General Curation' }}</strong></td>
                </tr>
                <tr>
                    <th>Company / Organization</th>
                    <td>{{ $inquiry['company_name'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Contact Phone</th>
                    <td>{{ $inquiry['phone'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Corporate Email</th>
                    <td>{{ $inquiry['email'] ?? 'N/A' }}</td>
                </tr>
                @if(!empty($inquiry['message']))
                <tr>
                    <th>Your Notes / Requirements</th>
                    <td>{{ $inquiry['message'] }}</td>
                </tr>
                @endif
            </table>

            <div class="highlight-box">
                <h4>What Happens Next?</h4>
                <p>
                    Our dedicated Institutional Gifting Concierge is reviewing your request. We will reach out to you within <strong>2 business hours</strong> with a customized volume pricing proposal, digital mockups, and packaging options.
                </p>
            </div>

            <div class="cta-section">
                <p style="font-size: 13px; color: #718096; margin-bottom: 12px;">Need instant assistance or urgent sample dispatch?</p>
                <a href="https://wa.me/916374390907?text=Hello%20House%20of%20KNP%2C%20I%20have%20submitted%20a%20corporate%20bulk%20order%20inquiry%20for%20{{ urlencode($inquiry['company_name'] ?? '') }}." target="_blank" class="btn-wa">
                    Chat with Concierge on WhatsApp
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="margin: 0 0 6px;"><strong>House of KNP Luxury Storefront</strong></p>
            <p style="margin: 0 0 6px;">Hotline: +91 6374390907 | Email: support@houseofknp.com</p>
            <p style="margin: 0;">&copy; {{ date('Y') }} House of KNP. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
