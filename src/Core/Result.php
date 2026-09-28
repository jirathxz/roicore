<?php

declare(strict_types=1);

namespace RoiCore\Core;

/**
 * Idiomatic Result<T> pattern for handling success or error explicitly.
 *
 * @template T
 */
final class Result
{
    /**
     * @param bool $isSuccess
     * @param T|null $value
     * @param string|null $error
     */
    private function __construct(
        public readonly bool $isSuccess,
        public readonly mixed $value = null,
        public readonly ?string $error = null
    ) {
    }

    /**
     * @template U
     * @param U $value
     * @return Result<U>
     */
    public static function ok(mixed $value = null): self
    {
        return new self(true, $value, null);
    }

    /**
     * @return Result<mixed>
     */
    public static function fail(string $error): self
    {
        return new self(false, null, $error);
    }

    public function isFailure(): bool
    {
        return !$this->isSuccess;
    }
}
