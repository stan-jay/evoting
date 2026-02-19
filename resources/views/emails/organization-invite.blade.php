<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organization Invite</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f8fafc; margin:0; padding:24px;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 640px; margin: 0 auto; background: #ffffff; border: 1px solid #e5e7eb; border-radius: 8px;">
        <tr>
            <td style="padding: 24px;">
                <h2 style="margin:0 0 12px; color:#111827;">You are invited to join {{ $invite->organization->name ?? config('app.name') }}</h2>
                <p style="margin:0 0 12px; color:#374151;">Your role: <strong>{{ ucfirst($invite->role) }}</strong></p>
                <p style="margin:0 0 20px; color:#374151;">
                    Use the button below to complete your account setup.
                    @if($invite->expires_at)
                        This invite expires on <strong>{{ $invite->expires_at->format('Y-m-d H:i') }}</strong>.
                    @endif
                </p>
                <p style="margin:0 0 20px;">
                    <a href="{{ $registerUrl }}" style="display:inline-block; background:#2563eb; color:#ffffff; text-decoration:none; padding:12px 18px; border-radius:6px;">Accept Invite</a>
                </p>
                <p style="margin:0; color:#6b7280; font-size:12px;">
                    If the button does not work, copy and paste this link into your browser:<br>
                    <a href="{{ $registerUrl }}" style="color:#2563eb;">{{ $registerUrl }}</a>
                </p>
            </td>
        </tr>
    </table>
</body>
</html>
