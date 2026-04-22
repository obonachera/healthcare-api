<?php

declare(strict_types=1);

namespace Lightit\Clinic\Domain\DataTransferObjects;

readonly class ClinicDto
{
    /**
     * @param array<int> $doctors
     */
    public function __construct(
        public string $name,
        public string $address,
        public array $doctors,
    ) {
    }
}
