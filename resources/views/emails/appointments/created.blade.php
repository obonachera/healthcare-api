<x-mail.layout title="Appointment Confirmed">
    <x-mail.header title="Appointment Confirmed" />

    <tr>
        <td style="padding:32px 40px 0;">
            <p style="margin:0;font-size:16px;color:#374151;line-height:1.6;">
                Hi <strong>{{ $patient->name }}</strong>,
            </p>
            <p style="margin:12px 0 0;font-size:16px;color:#374151;line-height:1.6;">
                Your appointment has been successfully booked. Here are the details:
            </p>
        </td>
    </tr>

    <x-mail.details-card>
        <x-mail.detail-row label="Doctor" :value="$appointment->doctor->name" />
        <x-mail.detail-row label="Clinic" :value="$appointment->clinic->name" />
        <x-mail.detail-row label="Date"   :value="$appointment->start_time->format('l, F j, Y')" />
        <x-mail.detail-row label="Time"   :value="$appointment->start_time->format('g:i A') . ' – ' . $appointment->end_time->format('g:i A')" />
    </x-mail.details-card>

    <tr>
        <td style="padding:0 40px 32px;">
            <p style="margin:0;font-size:15px;color:#6b7280;line-height:1.6;">
                Please arrive a few minutes early. If you need to reschedule or cancel, contact us as soon as possible.
            </p>
        </td>
    </tr>

    <x-mail.footer />
</x-mail.layout>
