<?php

namespace Session\Services;

use Session\Exceptions\SessionException;

interface SessionServiceInterface
{
    /**
     * @throws SessionException
     */
    public function startSession(): void;

    /**
     * @throws SessionException
     */
    public function isStarted(): bool;

    /**
     * @throws SessionException
     */
    public function getSessionId(): string;

    /**
     * @throws SessionException
     */
    public function regenerateSessionId(): void;

    /**
     * @return array<int|string,mixed>|string|float|int|bool|null
     *
     * @throws SessionException
     */
    public function get(string $key): array|string|float|int|bool|null;

    /**
     * @param array<int|string,mixed>|string|float|int|bool|null $value
     *
     * @throws SessionException
     */
    public function set(string $key, array|string|float|int|bool|null $value): void;

    /**
     * @throws SessionException
     */
    public function delete(string $key): void;

    /**
     * @throws SessionException
     */
    public function clear(): void;

    /**
     * @throws SessionException
     */
    public function has(string $key): bool;

    /**
     * @throws SessionException
     */
    public function destroySession(): void;
}
