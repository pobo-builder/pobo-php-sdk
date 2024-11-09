<?php

declare(strict_types=1);

namespace Pobo\Libs\Api;

use Pobo\Exceptions\ApiClientException;

class ApiClient
{
    private string $baseUrl;

    public function __construct(
        string $baseUrl,
        private ?string $token = null
    ) {
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Makes an HTTP request to the given endpoint.
     *
     * @param string $method HTTP method (e.g., 'GET', 'POST').
     * @param string $endpoint API endpoint to request.
     * @param array<string, mixed> $data Optional data to send with the request.
     * @return array<string, mixed> Decoded JSON response as an associative array, or null if decoding fails.
     * @throws ApiClientException If the request fails or the response cannot be decoded.
     */
    public function makeRequest(string $method, string $endpoint, array $data = []): array
    {
        $url = $this->baseUrl . '/' . ltrim($endpoint, '/');

        if (strtoupper($method) === 'GET' && $data !== []) {
            $queryString = http_build_query($data);
            $url .= '?' . $queryString;
        }

        $ch = curl_init($url);
        $headers = ['Content-Type: application/json'];
        if ($this->token !== null) {
            $headers[] = 'Authorization: Bearer ' . $this->token;
        }

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => strtoupper($method),
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_REFERER => $this->baseUrl,
            CURLOPT_POSTFIELDS => !empty($data) ? json_encode($data) : null,
        ]);

        $response = curl_exec($ch);

        if ($response === false) {
            throw new ApiClientException('CURL error: ' . curl_error($ch));
        }
        curl_close($ch);

        $decodedResponse = json_decode((string) $response, true);

        if (!is_array($decodedResponse)) {
            throw new ApiClientException('Invalid JSON response: ' . $response);
        }

        return $decodedResponse;
    }
}
