<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Confirmed</title>
</head>
<body style="margin:0;padding:0;background-color:#f4f7f9;font-family:Arial,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f7f9;padding:40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.08);">

                    {{-- Header --}}
                    <tr>
                        <td style="background-color:#2563eb;padding:32px 40px;text-align:center;">
                            <h1 style="margin:0;color:#ffffff;font-size:24px;font-weight:700;letter-spacing:-0.5px;">
                                Appointment Confirmed
                            </h1>
                        </td>
                    </tr>

                    {{-- Greeting --}}
                    <tr>
                        <td style="padding:32px 40px 0;">
                            <p style="margin:0;font-size:16px;color:#374151;line-height:1.6;">
                                Hi <strong>{{ $user->name }}</strong>,
                            </p>
                            <p style="margin:12px 0 0;font-size:16px;color:#374151;line-height:1.6;">
                                Your appointment has been successfully booked. Here are the details:
                            </p>
                        </td>
                    </tr>

                    {{-- Details card --}}
                    <tr>
                        <td style="padding:24px 40px;">
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f0f4ff;border-radius:6px;border-left:4px solid #2563eb;">
                                <tr>
                                    <td style="padding:24px;">
                                        <table width="100%" cellpadding="0" cellspacing="8">
                                            <tr>
                                                <td style="font-size:13px;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;padding-bottom:4px;" width="140">Doctor</td>
                                                <td style="font-size:15px;color:#111827;font-weight:600;">{{ $appointment->doctor->name }}</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size:13px;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;padding-top:12px;padding-bottom:4px;">Clinic</td>
                                                <td style="font-size:15px;color:#111827;font-weight:600;padding-top:12px;">{{ $appointment->clinic->name }}</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size:13px;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;padding-top:12px;padding-bottom:4px;">Date</td>
                                                <td style="font-size:15px;color:#111827;font-weight:600;padding-top:12px;">{{ $appointment->start_time->format('l, F j, Y') }}</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size:13px;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;padding-top:12px;padding-bottom:4px;">Time</td>
                                                <td style="font-size:15px;color:#111827;font-weight:600;padding-top:12px;">
                                                    {{ $appointment->start_time->format('g:i A') }} &ndash; {{ $appointment->end_time->format('g:i A') }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Body text --}}
                    <tr>
                        <td style="padding:0 40px 32px;">
                            <p style="margin:0;font-size:15px;color:#6b7280;line-height:1.6;">
                                Please arrive a few minutes early. If you need to reschedule or cancel, contact us as soon as possible.
                            </p>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background-color:#f9fafb;border-top:1px solid #e5e7eb;padding:24px 40px;text-align:center;">
                            <p style="margin:0;font-size:13px;color:#9ca3af;">
                                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
