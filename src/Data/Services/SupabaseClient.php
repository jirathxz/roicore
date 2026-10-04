<?php

declare(strict_types=1);

namespace RoiCore\Data\Services;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use RuntimeException;

final class SupabaseClient
{
    private ClientInterface $httpClient;
    private string $baseUrl;
    private string $apiKey;

    public function __construct(
        ?string $baseUrl = null,
        ?string $apiKey = null,
        ?ClientInterface $httpClient = null
    ) {
        $this->baseUrl = rtrim($baseUrl ?? ($_ENV['SUPABASE_URL'] ?? (getenv('SUPABASE_URL') ?: 'http://127.0.0.1:54321')), '/');
        $this->apiKey = $apiKey ?? ($_ENV['SUPABASE_ANON_KEY'] ?? (getenv('SUPABASE_ANON_KEY') ?: ''));

        $this->httpClient = $httpClient ?? new Client([
            'timeout' => 5.0,
            'connect_timeout' => 2.0,
        ]);
    }

    public function isConfigured(): bool
    {
        return !empty($this->baseUrl) && !empty($this->apiKey);
    }

    /**
     * @param string $table
     * @param array<string, mixed> $queryParams
     * @return array<mixed>
     */
    public function select(string $table, array $queryParams = []): array
    {
        $url = "{$this->baseUrl}/rest/v1/{$table}";

        try {
            $response = $this->httpClient->request('GET', $url, [
                'headers' => $this->getHeaders(),
                'query' => $queryParams,
            ]);

            $body = (string) $response->getBody();
            return json_decode($body, true, 512, JSON_THROW_ON_ERROR) ?? [];
        } catch (GuzzleException $e) {
            throw new RuntimeException("Supabase SELECT query failed on '{$table}': " . $e->getMessage(), (int) $e->getCode(), $e);
        }
    }

    /**
     * @param string $table
     * @param array<string, mixed>|array<array<string, mixed>> $data
     * @return array<mixed>
     */
    public function insert(string $table, array $data): array
    {
        $url = "{$this->baseUrl}/rest/v1/{$table}";

        try {
            $response = $this->httpClient->request('POST', $url, [
                'headers' => array_merge($this->getHeaders(), [
                    'Prefer' => 'return=representation',
                ]),
                'json' => $data,
            ]);

            $body = (string) $response->getBody();
            return json_decode($body, true, 512, JSON_THROW_ON_ERROR) ?? [];
        } catch (GuzzleException $e) {
            throw new RuntimeException("Supabase INSERT query failed on '{$table}': " . $e->getMessage(), (int) $e->getCode(), $e);
        }
    }

    /**
     * @param string $table
     * @param array<string, mixed> $data
     * @param array<string, string> $filters
     * @return array<mixed>
     */
    public function update(string $table, array $data, array $filters = []): array
    {
        $url = "{$this->baseUrl}/rest/v1/{$table}";

        try {
            $response = $this->httpClient->request('PATCH', $url, [
                'headers' => array_merge($this->getHeaders(), [
                    'Prefer' => 'return=representation',
                ]),
                'query' => $filters,
                'json' => $data,
            ]);

            $body = (string) $response->getBody();
            return json_decode($body, true, 512, JSON_THROW_ON_ERROR) ?? [];
        } catch (GuzzleException $e) {
            throw new RuntimeException("Supabase UPDATE query failed on '{$table}': " . $e->getMessage(), (int) $e->getCode(), $e);
        }
    }

    /**
     * @return array<string, string>
     */
    private function getHeaders(): array
    {
        return [
            'apikey' => $this->apiKey,
            'Authorization' => "Bearer {$this->apiKey}",
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }
}
