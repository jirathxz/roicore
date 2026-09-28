<?php

declare(strict_types=1);

namespace RoiCore\Presentation\Http;

final class Request
{
    /**
     * @param array<string, mixed> $queryParams
     * @param array<string, mixed> $body
     * @param array<string, string> $headers
     */
    public function __construct(
        public readonly string $method,
        public readonly string $path,
        public readonly array $queryParams,
        public readonly array $body,
        public readonly array $headers
    ) {
    }

    public static function fromGlobals(): self
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $path = parse_url($uri, PHP_URL_PATH) ?? '/';

        $queryParams = $_GET;

        $rawBody = file_get_contents('php://input');
        $body = [];
        if ($rawBody !== false && trim($rawBody) !== '') {
            $parsed = json_decode($rawBody, true);
            if (is_array($parsed)) {
                $body = $parsed;
            }
        }
        if (empty($body) && !empty($_POST)) {
            $body = $_POST;
        }

        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (str_starts_with($key, 'HTTP_')) {
                $headerName = strtolower(str_replace('_', '-', substr($key, 5)));
                $headers[$headerName] = (string) $value;
            }
        }

        return new self($method, $path, $queryParams, $body, $headers);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->body[$key] ?? $this->queryParams[$key] ?? $default;
    }
}
