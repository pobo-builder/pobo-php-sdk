<?php

declare(strict_types=1);

namespace Pobo\Libs\Categories;

use Pobo\Exceptions\ApiClientException;
use Pobo\Libs\Api\ApiClient;
use Pobo\Libs\Models\CategoryModel;

final class CategoryClient
{
    public function __construct(
        private ApiClient $apiClient
    ) {
    }

    /**
     * Lists all categories.
     *
     * @return CategoryModel[]
     * @throws ApiClientException
     */
    public function list(): array
    {
        /**
         * @var array{
         *     result?: array{
         *         category: array<int, array{
         *             id: int,
         *             url: string,
         *             translations: array<string, array<string, string>>,
         *             is_visible: bool,
         *             is_delete: bool
         *         }>
         *     }|null,
         *     error?: string
         * } $categories
         */
        $categories = $this->apiClient->makeRequest('POST', '/api/v2/category/grid/');

        if (isset($categories['error'])) {
            throw new ApiClientException((string) $categories['error']);
        }

        if (!isset($categories['result']['category']) || !is_array($categories['result']['category'])) {
            throw new ApiClientException('Unexpected response format');
        }

        return CategoryModel::fromArrayCollection($categories['result']['category']);
    }
}
