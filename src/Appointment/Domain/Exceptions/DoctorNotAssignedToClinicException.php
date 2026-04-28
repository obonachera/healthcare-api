<?php

declare(strict_types=1);

namespace Lightit\Appointment\Domain\Exceptions;

use Illuminate\Http\JsonResponse;
use Lightit\Shared\App\Exceptions\Http\HttpException;

class DoctorNotAssignedToClinicException extends HttpException
{
    #[\Override]
    protected int $status = JsonResponse::HTTP_UNPROCESSABLE_ENTITY;

    #[\Override]
    protected string $errorCode = 'doctor_not_assigned_to_clinic';

    #[\Override]
    protected $message = 'Doctor is not assigned to the selected clinic.';
}
