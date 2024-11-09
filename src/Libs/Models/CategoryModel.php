<?php

declare(strict_types=1);

namespace Pobo\Libs\Models;

final class CategoryModel extends BaseModel
{
    /**
     * @param int $id Unique identifier for the category.
     * @param string $url URL link to the category page.
     * @param array<string, array<string, string>> $translations Array of translations, each keyed by locale.
     * @param bool $isVisible Determines if the category is visible to users.
     * @param bool $isDelete Marks whether the category has been deleted.
     */
    private function __construct(
        private int $id,
        private string $url,
        private array $translations,
        private bool $isVisible,
        private bool $isDelete,
    ) {
    }

    /**
     * @param array{id: int, url: string, translations: array<string, array<string, string>>, is_visible: bool, is_delete: bool} $data
     */
    public static function fromArray(array $data): static
    {
        return new self(
            (int) $data['id'],
            (string) $data['url'],
            (array) $data['translations'],
            (bool) $data['is_visible'],
            (bool) $data['is_delete'],
        );
    }

    /**
     * @param array<int, array{id: int, url: string, translations: array<string, array<string, string>>, is_visible: bool, is_delete: bool}> $data
     * @return array<int, self>
     */
    public static function fromArrayCollection(array $data): array
    {
        return array_map(fn(array $modelData) => self::fromArray($modelData), $data);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getName(string $locale = 'default'): ?string
    {
        return $this->translations[$locale]['name'] ?? null;
    }

    public function isVisible(): bool
    {
        return $this->isVisible;
    }

    public function isDelete(): bool
    {
        return $this->isDelete;
    }
}
