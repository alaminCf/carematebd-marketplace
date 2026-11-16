<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Care Desk Inquiry — CareMate BD</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f1f5f9; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #1e293b;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #f1f5f9; padding: 30px 15px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(10, 57, 74, 0.08); border: 1px solid #e2e8f0;">
                    <!-- Brand Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #0a394a 0%, #15798e 100%); padding: 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 24px; font-weight: 800; letter-spacing: -0.5px;">
                                CareMate <span style="color: #5eead4;">BD</span>
                            </h1>
                            <p style="margin: 6px 0 0 0; color: #ccfbf1; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em;">
                                Care Coordination Desk Inquiry
                            </p>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 32px 28px;">
                            <div style="background-color: #f0fdfa; border-left: 4px solid #14b8a6; padding: 14px 18px; border-radius: 8px; margin-bottom: 24px;">
                                <p style="margin: 0; font-size: 14px; color: #0f766e; font-weight: 600;">
                                    A new inquiry was submitted via the CareMate BD website contact form.
                                </p>
                            </div>

                            <h2 style="margin: 0 0 16px 0; font-size: 18px; font-weight: 700; color: #0f172a;">
                                Inquiry Details
                            </h2>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 24px; border-collapse: collapse;">
                                <tr>
                                    <td style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; width: 140px; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.04em;">
                                        Full Name
                                    </td>
                                    <td style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 15px; font-weight: 700; color: #0f172a;">
                                        {{ $contactMessage->name }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.04em;">
                                        Email Address
                                    </td>
                                    <td style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 15px; color: #0a394a; font-weight: 600;">
                                        <a href="mailto:{{ $contactMessage->email }}" style="color: #15798e; text-decoration: none;">
                                            {{ $contactMessage->email }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.04em;">
                                        Phone Number
                                    </td>
                                    <td style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 15px; color: #0f172a; font-weight: 600;">
                                        @if($contactMessage->phone)
                                            <a href="tel:{{ $contactMessage->phone }}" style="color: #0f172a; text-decoration: none;">
                                                {{ $contactMessage->phone }}
                                            </a>
                                        @else
                                            <span style="color: #94a3b8; font-style: italic;">Not provided</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.04em;">
                                        Subject / Need
                                    </td>
                                    <td style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 15px; font-weight: 700; color: #0f172a;">
                                        {{ $contactMessage->subject }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 0; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.04em;">
                                        Received At
                                    </td>
                                    <td style="padding: 10px 0; font-size: 13px; color: #64748b;">
                                        {{ $contactMessage->created_at->format('M d, Y h:i A') }} (IP: {{ $contactMessage->ip_address ?? 'N/A' }})
                                    </td>
                                </tr>
                            </table>

                            <h3 style="margin: 0 0 10px 0; font-size: 15px; font-weight: 700; color: #0f172a;">
                                Detailed Message:
                            </h3>
                            <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px 20px; font-size: 14px; line-height: 1.6; color: #334155; white-space: pre-line; margin-bottom: 28px;">
                                {{ $contactMessage->message }}
                            </div>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center">
                                        <a href="mailto:{{ $contactMessage->email }}?subject=Re:%20{{ rawurlencode($contactMessage->subject) }}" style="display: inline-block; background-color: #0a394a; color: #ffffff; text-decoration: none; padding: 12px 28px; border-radius: 10px; font-weight: 700; font-size: 14px;">
                                            Reply to {{ $contactMessage->name }} &rarr;
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; padding: 20px 28px; text-align: center; border-top: 1px solid #e2e8f0; font-size: 12px; color: #94a3b8;">
                            {{ $siteSettings['platform_name'] ?? 'CareMate BD' }} &bull; {{ $siteSettings['office_address'] ?? 'E-14/X, ICT Tower (14th Floor), Agargaon, Dhaka-1207, Bangladesh' }}<br>
                            Hotline: {{ $siteSettings['emergency_hotline'] ?? '+880 1610-296460' }} &bull; Email: {{ $siteSettings['contact_email'] ?? 'contact@carematebd.com' }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
