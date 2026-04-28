<?php

declare(strict_types=1);

namespace Lightit\Shared\App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Lightit\Appointment\App\Events\AppointmentCreatedEvent;
use Lightit\Appointment\App\Listeners\SendAppointmentCreatedNotificationListener;
use Lightit\Shared\App\Events\TestEvent;
use Lightit\Shared\App\Listeners\TestListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    #[\Override]
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        TestEvent::class => [
            TestListener::class,
        ],
        AppointmentCreatedEvent::class => [
            SendAppointmentCreatedNotificationListener::class,
        ],
    ];

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
