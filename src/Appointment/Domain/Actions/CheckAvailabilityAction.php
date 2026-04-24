<?php

declare(strict_types=1);

namespace Lightit\Appointment\Domain\Actions;

use Carbon\CarbonImmutable;
use Lightit\Appointment\Domain\Models\Appointment;

class CheckAvailabilityAction
{
    public function execute(
        string $column,
        int $entityId,
        CarbonImmutable $startTime,
        CarbonImmutable $endTime,
        int|null $excludeAppointmentId = null,
    ): bool {
        return ! Appointment::query()
            ->where($column, $entityId)
            ->where('start_time', '<', $endTime)
            ->where('end_time', '>', $startTime)
            ->when($excludeAppointmentId, fn ($q) => $q->where('id', '!=', $excludeAppointmentId))
            ->exists();
    }
}
