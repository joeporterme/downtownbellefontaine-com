@php($submitter = $event->submittedBy)
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $isUpdate ? 'Event Changed' : 'New Event Submission' }}</title>
</head>
<body style="margin:0;padding:24px;background:#f3f4f6;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;color:#111827;line-height:1.5;">
    <div style="max-width:560px;margin:0 auto;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.08);">
        <div style="background:#01434c;color:#ffffff;padding:20px 24px;">
            <p style="margin:0;font-size:13px;letter-spacing:0.05em;text-transform:uppercase;color:#fbcdb8;">Downtown Bellefontaine</p>
            <h1 style="margin:4px 0 0;font-size:20px;font-weight:700;">{{ $isUpdate ? 'Event Changed, Needs Re-approval' : 'New Event to Approve' }}</h1>
        </div>
        <div style="padding:24px;">
            @if($isUpdate)
            <p style="margin:0 0 16px;font-size:14px;color:#374151;">
                This event was already approved. The owner edited it, so it is hidden from the calendar until it is approved again.
            </p>
            @endif

            <table style="width:100%;border-collapse:collapse;font-size:14px;">
                <tr>
                    <td style="padding:6px 0;color:#6b7280;width:110px;">Event</td>
                    <td style="padding:6px 0;font-weight:600;">{{ $event->title }}</td>
                </tr>
                <tr>
                    <td style="padding:6px 0;color:#6b7280;">Date</td>
                    <td style="padding:6px 0;">{{ $event->event_date->format('l, M j, Y') }}@if($event->formatted_time), {{ $event->formatted_time }}@endif</td>
                </tr>
                @if($event->location_name || $event->full_address)
                <tr>
                    <td style="padding:6px 0;color:#6b7280;">Where</td>
                    <td style="padding:6px 0;">{{ collect([$event->location_name, $event->full_address])->filter()->implode(', ') }}</td>
                </tr>
                @endif
                @if($event->business)
                <tr>
                    <td style="padding:6px 0;color:#6b7280;">Business</td>
                    <td style="padding:6px 0;">{{ $event->business->name }}</td>
                </tr>
                @endif
                @if($submitter)
                <tr>
                    <td style="padding:6px 0;color:#6b7280;">Submitted by</td>
                    <td style="padding:6px 0;">{{ $submitter->name }} (<a href="mailto:{{ $submitter->email }}" style="color:#e25a1f;text-decoration:none;">{{ $submitter->email }}</a>)</td>
                </tr>
                @endif
                @if($event->more_info_url)
                <tr>
                    <td style="padding:6px 0;color:#6b7280;">More info</td>
                    <td style="padding:6px 0;"><a href="{{ $event->more_info_url }}" style="color:#e25a1f;text-decoration:none;word-break:break-all;">{{ $event->more_info_url }}</a></td>
                </tr>
                @endif
                <tr>
                    <td style="padding:6px 0;color:#6b7280;">Image</td>
                    <td style="padding:6px 0;">{{ $event->featured_image ? 'Yes' : 'None' }}</td>
                </tr>
            </table>

            @if($event->description)
            <div style="margin-top:20px;padding:16px;background:#f9fafb;border-left:3px solid #f3773d;border-radius:6px;">
                <p style="margin:0;font-size:11px;letter-spacing:0.06em;text-transform:uppercase;color:#6b7280;font-weight:600;">Description</p>
                <p style="margin:8px 0 0;white-space:pre-wrap;">{{ $event->description }}</p>
            </div>
            @endif

            <p style="margin:24px 0 0;">
                <a href="{{ \App\Filament\Resources\Events\EventResource::getUrl('edit', ['record' => $event]) }}"
                   style="display:inline-block;background:#e25a1f;color:#ffffff;font-weight:600;font-size:14px;padding:10px 18px;border-radius:8px;text-decoration:none;">
                    Review and approve
                </a>
            </p>

            <p style="margin-top:20px;font-size:12px;color:#6b7280;">
                Reply directly to this email to reach {{ $submitter->name ?? 'the submitter' }}.
            </p>
        </div>
    </div>
</body>
</html>
