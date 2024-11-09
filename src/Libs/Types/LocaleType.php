<?php

declare(strict_types=1);

namespace Pobo\Libs\Types;

use Pobo\Exceptions\InvalidLocaleException;

abstract class LocaleType
{
    public const LOCALE_CZ = 'CZ';
    public const LOCALE_HU = 'HU';
    public const LOCALE_SPACE = 'SPACE';

    public static function isValidLocale(string $locale): bool
    {
        return in_array(
            $locale,
            [self::LOCALE_CZ, self::LOCALE_HU, self::LOCALE_SPACE],
            true
        );
    }

    public static function getBaseUrl(string $locale): string
    {
        return match ($locale) {
            self::LOCALE_CZ => 'https://www.pobo.cz',
            self::LOCALE_HU => 'https://www.pobo.hu',
            self::LOCALE_SPACE => 'https://www.pobo.space',
            default => throw new InvalidLocaleException($locale),
        };
    }
}
