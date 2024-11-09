<?php

declare(strict_types=1);

namespace Pobo\Libs\Models;

use DateTimeImmutable;
use InvalidArgumentException;

final class ProductModel extends BaseModel
{
    /**
     * @param int $id Internal identifier for the product.
     * @param string $name Display name of the product.
     * @param string $guid Globally unique identifier (GUID) for the product, formatted as a UUID string (e.g., "302b8ad6-07d5-11ec-b98c-0cc47a6c9370").
     * @param string|null $shortDescription Brief description highlighting key features or details about the product, or null if not provided.
     * @param string|null $imagePreview URL of the product's preview image, or null if not available.
     * @param bool $isVisible Indicates whether the product is visible to users.
     * @param bool $isFavourite Specifies if the product is marked as a favourite by the user.
     * @param DateTimeImmutable $createdAt Date and time when the product was created.
     */
    private function __construct(
        private int $id,
        private string $name,
        private string $guid,
        private ?string $shortDescription,
        private ?string $imagePreview,
        private bool $isVisible,
        private bool $isFavourite,
        private DateTimeImmutable $createdAt,
    ) {
    }

    /**
     * @param array{
     *     id: int,
     *     name: string,
     *     guid: string,
     *     short_description?: string|null,
     *     image_preview?: string|null,
     *     is_visible: bool,
     *     is_favourite: bool,
     *     created_at: DateTimeImmutable|string
     * } $data
     * @throws InvalidArgumentException if 'created_at' is not a valid date format
     */
    public static function fromArray(array $data): static
    {
        return new self(
            (int) $data['id'],
            (string) $data['name'],
            (string) $data['guid'],
            $data['short_description'] ?? null,
            $data['image_preview'] ?? null,
            $data['is_visible'] ?? false,
            $data['is_favourite'] ?? false,
            $data['created_at'] instanceof DateTimeImmutable ? $data['created_at'] : new DateTimeImmutable((string) $data['created_at'])
        );
    }

    /**
     * @param array<array{
     *     id: int,
     *     name: string,
     *     guid: string,
     *     short_description: string|null,
     *     image_preview: string|null,
     *     is_visible: bool,
     *     is_favourite: bool,
     *     created_at: DateTimeImmutable
     * }> $data
     * @return array<int, self>
     */
    public static function fromArrayCollection(array $data): array
    {
        return array_map(fn(array $modelData): self => self::fromArray($modelData), $data);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getGuid(): string
    {
        return $this->guid;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function getImagePreview(): ?string
    {
        return $this->imagePreview;
    }

    public function isVisible(): bool
    {
        return $this->isVisible;
    }

    public function isFavourite(): bool
    {
        return $this->isFavourite;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
