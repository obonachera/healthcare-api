<?php

declare(strict_types=1);

namespace Lightit\Appointment\Domain\DataTransferObjects;

use Carbon\CarbonImmutable;

readonly class AppointmentDto
{
    public function __construct(
        public int $doctorId,
        public int $patientId,
        public int $clinicId,
        public CarbonImmutable $startTime,
        public CarbonImmutable $endTime,
    ) {
    }
}
