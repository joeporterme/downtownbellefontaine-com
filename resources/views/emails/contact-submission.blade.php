<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New Website Contact Message</title>
</head>
<body style="margin:0;padding:24px;background:#f3f4f6;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;color:#111827;line-height:1.5;">
    <div style="max-width:560px;margin:0 auto;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.08);">
        <div style="background:#01434c;color:#ffffff;padding:20px 24px;">
            <p style="margin:0;font-size:13px;letter-spacing:0.05em;text-transform:uppercase;color:#fbcdb8;">Downtown Bellefontaine</p>
            <h1 style="margin:4px 0 0;font-size:20px;font-weight:700;">New Contact Message</h1>
        </div>
        <div style="padding:24px;">
            <table style="width:100%;border-collapse:collapse;font-size:14px;">
                <tr>
                    <td style="padding:6px 0;color:#6b7280;width:90px;">Name</td>
                    <td style="padding:6px 0;font-weight:600;">{{ $contactMessage->name }}</td>
                </tr>
                <tr>
                    <td style="padding:6px 0;color:#6b7280;">Email</td>
                    <td style="padding:6px 0;"><a href="mailto:{{ $contactMessage->email }}" style="color:#e25a1f;text-decoration:none;">{{ $contactMessage->email }}</a></td>
                </tr>
                @if($contactMessage->phone)
                <tr>
                    <td style="padding:6px 0;color:#6b7280;">Phone</td>
                    <td style="padding:6px 0;">{{ $contactMessage->phone }}</td>
                </tr>
                @endif
                @if($contactMessage->subject)
                <tr>
                    <td style="padding:6px 0;color:#6b7280;">Subject</td>
                    <td style="padding:6px 0;">{{ $contactMessage->subject }}</td>
                </tr>
                @endif
                <tr>
                    <td style="padding:6px 0;color:#6b7280;">Received</td>
                    <td style="padding:6px 0;">{{ $contactMessage->created_at->format('M j, Y g:i A') }}</td>
                </tr>
            </table>

            <div style="margin-top:20px;padding:16px;background:#f9fafb;border-left:3px solid #f3773d;border-radius:6px;">
                <p style="margin:0;font-size:11px;letter-spacing:0.06em;text-transform:uppercase;color:#6b7280;font-weight:600;">Message</p>
                <p style="margin:8px 0 0;white-space:pre-wrap;">{{ $contactMessage->message }}</p>
            </div>

            <p style="margin-top:20px;font-size:12px;color:#6b7280;">
                Reply directly to this email to respond to {{ $contactMessage->name }}.
            </p>
        </div>
    </div>
</body>
</html>
