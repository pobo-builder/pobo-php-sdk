<?php

declare(strict_types=1);

namespace Pobo;

use Pobo\Exceptions\ApiClientException;
use Pobo\Exceptions\AuthenticationException;
use Pobo\Exceptions\InvalidLocaleException;
use Pobo\Libs\Api\ApiClient;
use Pobo\Libs\Types\LocaleType;

final class UserClient
{
    private ApiClient $apiClient;
    private string $baseUrl;
    private ?string $jwtToken = null;

    public function __construct(
        private string $username,
        private string $password,
        string $locale = LocaleType::LOCALE_CZ
    ) {
        $this->setLocale($locale);
        $this->apiClient = new ApiClient($this->baseUrl);
        $this->authenticate();
    }

    private function authenticate(): void
    {
        if ($this->jwtToken !== null) {
            return;
        }

        /**
         * @var array{token: string}|array{error: string} $response
         */
        $response = $this->apiClient->makeRequest(
            'POST',
            '/api/v2/user/token/',
            ['username' => $this->username, 'password' => $this->password]
        );

        match (true) {
            isset($response['token']) => $this->jwtToken = $response['token'],
            isset($response['error']) => throw new AuthenticationException($response['error']),
            default => throw new ApiClientException('Unexpected response format'),
        };
    }

    /**
     * Sets the locale and its corresponding base URL for the API.
     *
     * @param string $locale
     * @return void
     * @throws InvalidLocaleException
     */
    private function setLocale(string $locale): void
    {
        if (!LocaleType::isValidLocale($locale)) {
            throw new InvalidLocaleException($locale);
        }

        $this->baseUrl = LocaleType::getBaseUrl($locale);
    }

    public function getToken(): string
    {
        if ($this->jwtToken === null) {
            throw new AuthenticationException('Authentication error: Missing or invalid JWT token.');
        }

        return $this->jwtToken;
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * Authenticates the user by sending credentials to the API and retrieves a JWT token for session management.
     *
     * @return void
     * @throws ApiClientException
     */
    public function logout(): void
    {
        if ($this->jwtToken === null) {
            throw new ApiClientException('You are not logged in.');
        }

        /**
         * @var array{result?: string, error?: string} $response
         */
        $response = $this->apiClient
            ->setToken($this->jwtToken)
            ->makeRequest(
                'GET',
                '/api/v2/user/logout/'
            );

        if (isset($response['error'])) {
            throw new ApiClientException(
                $response['error']
            );
        }

        $this->jwtToken = null;
    }
}
