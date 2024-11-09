<?php

declare(strict_types=1);

namespace Pobo\Libs\Models;

abstract class BaseModel
{
    /**
     * @param array<string, mixed> $data
     * @return static
     */
    abstract public static function fromArray(array $data): static;

    /**
     * @param array<int, array<string, mixed>> $data
     * @return array<int, static>
     */
    public static function fromArrayCollection(array $data): array
    {
        return array_map(fn(array $modelData) => static::fromArray($modelData), $data);
    }
}
