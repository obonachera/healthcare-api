<?php

declare(strict_types=1);

namespace Lightit\Authentication\Domain\Actions;

use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Lightit\Authentication\Domain\DataTransferObjects\LoginDto;
use Lightit\Patients\Domain\Models\Patient;
use PHPOpenSourceSaver\JWTAuth\Factory as JWTAuth;
use PHPOpenSourceSaver\JWTAuth\JWTGuard;

final class LoginByPatientAction
{
    public function __construct(
        private readonly AuthFactory $factory,
        private readonly JWTAuth $jwtAuth,
    ) {
    }

    public function execute(Patient $patient): LoginDto
    {
        /** @var JWTGuard $guard */
        $guard = $this->factory->guard();

        /** @var string $token */
        $token = $guard->tokenById(id: $patient->getKey());

        return new LoginDto(
            accessToken: $token,
            tokenType: 'bearer',
            expiresIn: $this->jwtAuth->getTTL() * 60,
        );
    }
}
