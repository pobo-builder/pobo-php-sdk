<?php

declare(strict_types=1);

namespace Pobo\Exceptions;

use RuntimeException;

class AuthenticationException extends RuntimeException
{
    public function __construct(
        string $message
    ) {
        parent::__construct(
            sprintf('Login failed: %s', $message)
        );
    }
}
