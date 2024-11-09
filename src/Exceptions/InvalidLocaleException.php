<?php

declare(strict_types=1);

namespace Pobo\Exceptions;

use Exception;

class InvalidLocaleException extends Exception
{
    public function __construct(
        string $locale
    ) {
        parent::__construct(
            sprintf(
                'Unsupported locale: %s',
                $locale
            )
        );
    }
}
