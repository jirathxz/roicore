<?php

declare(strict_types=1);

namespace RoiCore\Presentation\Http;

final class Response
{
    /**
     * @param array<string, string> $headers
     */
    public function __construct(
        public readonly int $statusCode,
        public readonly mixed $data,
        public readonly array $headers = []
    ) {
    }

    public static function json(mixed $data, int $statusCode = 200, array $headers = []): self
    {
        return new self($statusCode, $data, array_merge(['Content-Type' => 'application/json; charset=utf-8'], $headers));
    }

    public static function success(mixed $data, string $message = 'ดำเนินการสำเร็จ', int $statusCode = 200): self
    {
        return self::json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    public static function error(string $message, int $statusCode = 400, mixed $errors = null): self
    {
        return self::json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $statusCode);
    }

    public function send(): void
    {
        http_response_code($this->statusCode);
        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }

        if (is_array($this->data) || is_object($this->data)) {
            echo json_encode($this->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } else {
            echo (string) $this->data;
        }
    }
}
