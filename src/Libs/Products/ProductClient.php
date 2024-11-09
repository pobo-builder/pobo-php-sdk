<?php

declare(strict_types=1);

namespace Pobo\Libs\Products;

use Pobo\Exceptions\ApiClientException;
use Pobo\Libs\Api\ApiClient;
use Pobo\Libs\Models\ProductModel;
use DateTimeImmutable;

final class ProductClient
{
    public function __construct(
        private ApiClient $apiClient
    ) {
    }

    /**
     * Lists all products with optional category filtering and pagination.
     *
     * @param int $page Pagination page number.
     * @param int[] $categoryIds List of category IDs to filter products.
     * @return ProductModel[]
     * @throws ApiClientException
     */
    public function list(int $page = 1, array $categoryIds = []): array
    {
        /** @var array{result: array{products: array<array{id: int, name: string, guid: string, short_description?: string|null, image_preview?: string|null, is_visible: bool, is_favourite: bool, created_at: DateTimeImmutable|string}>}} $products */
        $products = $this
            ->apiClient
            ->makeRequest(
                'GET',
                '/api/v2/product/list-all/',
                [
                    'page' => $page,
                    'categories' => $categoryIds,
                ]
            );

        return ProductModel::fromArrayCollection(array_map(
            static fn(array $product): array => [
                'id' => (int) $product['id'],
                'name' => (string) $product['name'],
                'guid' => (string) $product['guid'],
                'short_description' => $product['short_description'] ?? null,
                'image_preview' => $product['image_preview'] ?? null,
                'is_visible' => (bool) $product['is_visible'],
                'is_favourite' => (bool) $product['is_favourite'],
                'created_at' => $product['created_at'] instanceof DateTimeImmutable
                    ? $product['created_at']
                    : new DateTimeImmutable((string) $product['created_at']),
            ],
            $products['result']['products']
        ));
    }

    /**
     * Bulk imports products into the system.
     *
     * @param array<array{
     *     guid: string,
     *     name: string,
     *     short_description?: string|null,
     *     is_visible: bool,
     *     categories: int[],
     *     images: array<array{src: string, main_image?: bool}>
     * }> $values Array of products to be imported.
     * @return array{
     *     success: int,
     *     skipped: int,
     *     errors: array<array{code: int, message: string}>
     * }
     * @throws ApiClientException
     */
    public function bulkImport(array $values): array
    {
        /** @var array{
         *     success: int,
         *     skipped: int,
         *     errors: array<array{code: int, message: string}>
         * } $response
         */
        $response = $this
            ->apiClient
            ->makeRequest(
                'POST',
                '/api/v2/public/product',
                $values
            );

        return $response;
    }
}
