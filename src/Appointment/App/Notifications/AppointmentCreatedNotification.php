<?php

declare(strict_types=1);

namespace Lightit\Appointment\App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Lightit\Appointment\Domain\Models\Appointment;
use Lightit\Users\Domain\Models\User;

class AppointmentCreatedNotification extends Notification implements ShouldQueue, ShouldBeEncrypted
{
    use Queueable;

    public function __construct(
        private readonly Appointment $appointment,
    ) {
    }

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(User $notifiable): MailMessage
    {
        return new MailMessage()
            ->subject('Your Appointment is Confirmed')
            ->view('emails.appointments.created', [
                'appointment' => $this->appointment,
                'user'        => $notifiable,
            ]);
    }
}
