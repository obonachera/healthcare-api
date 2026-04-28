<?php

declare(strict_types=1);

namespace Lightit\Appointment\Domain\Exceptions;

use Illuminate\Http\JsonResponse;
use Lightit\Shared\App\Exceptions\Http\HttpException;

class DoctorNotAvailableException extends HttpException
{
    #[\Override]
    protected int $status = JsonResponse::HTTP_UNPROCESSABLE_ENTITY;

    #[\Override]
    protected string $errorCode = 'doctor_not_available';

    #[\Override]
    protected $message = 'The doctor already has an appointment during the requested time.';
}
