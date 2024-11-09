<?php

declare(strict_types=1);

namespace Pobo;

use Pobo\Libs\Api\ApiClient;
use Pobo\Libs\Categories\CategoryClient;
use Pobo\Libs\Products\ProductClient;

final class PoboClient
{
    private ApiClient $apiClient;

    public function __construct(
        private UserClient $userClient
    ) {
        $this->setApiClient();
    }

    public function categories(): CategoryClient
    {
        return new CategoryClient($this->apiClient);
    }

    public function products(): ProductClient
    {
        return new ProductClient($this->apiClient);
    }

    private function setApiClient(): void
    {
        $this->apiClient = new ApiClient(
            $this->userClient->getBaseUrl(),
            $this->userClient->getToken()
        );
    }
}
