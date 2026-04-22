<?php

declare(strict_types=1);

namespace Lightit\Doctor\Domain\DataTransferObjects;

readonly class DoctorDto
{
    /**
     * @param array<int> $clinics Array of clinic IDs
     */
    public function __construct(
        public string $name,
        public array $clinics,
    ) {
    }
}
