<?php

namespace Session\Services;

use Session\Exceptions\SessionException;

class SessionService implements SessionServiceInterface
{
    /**
     * @throws SessionException
     */
    public function __construct(
        \SessionHandlerInterface&\SessionIdInterface $sessionHandler,
    ) {
        $this->configure();

        $this->setSessionHandler($sessionHandler);
    }

    /**
     * @throws SessionException
     */
    public function startSession(): void
    {
        if ($this->isStarted()) {
            return;
        }

        if (!session_start()) {
            throw new SessionException('Failed to start the session.');
        }
    }

    /**
     * @throws SessionException
     */
    public function isStarted(): bool
    {
        return PHP_SESSION_ACTIVE === session_status();
    }

    /**
     * @throws SessionException
     */
    public function getSessionId(): string
    {
        $this->assertStarted();

        $sessionId = session_id();

        if (!is_string($sessionId)) {
            throw new SessionException('Failed to get the session id.');
        }

        return $sessionId;
    }

    /**
     * @throws SessionException
     */
    public function regenerateSessionId(): void
    {
        $this->assertStarted();

        if (!session_regenerate_id(true)) {
            throw new SessionException('Failed to regenerate the session id.');
        }
    }

    /**
     * @return array<int|string,mixed>|string|float|int|bool|null
     *
     * @throws SessionException
     */
    public function get(string $key): array|string|float|int|bool|null
    {
        $this->assertStarted();

        if (!$this->has($key)) {
            throw new SessionException('Failed to get the value from the session.');
        }

        return $_SESSION[$key];
    }

    /**
     * @param array<int|string,mixed>|string|float|int|bool|null $value
     *
     * @throws SessionException
     */
    public function set(string $key, array|string|float|int|bool|null $value): void
    {
        $this->assertStarted();

        $_SESSION[$key] = $value;
    }

    /**
     * @throws SessionException
     */
    public function delete(string $key): void
    {
        $this->assertStarted();

        unset($_SESSION[$key]);
    }

    /**
     * @throws SessionException
     */
    public function clear(): void
    {
        $this->assertStarted();

        if (!session_unset()) {
            throw new SessionException('Failed to clear the session.');
        }
    }

    /**
     * @throws SessionException
     */
    public function has(string $key): bool
    {
        $this->assertStarted();

        return array_key_exists($key, $_SESSION);
    }

    /**
     * @throws SessionException
     */
    public function destroySession(): void
    {
        $this->assertStarted();

        $this->clear();

        if (!session_destroy()) {
            throw new SessionException('Failed to destroy the session.');
        }
    }

    protected function configure(): void
    {
        ini_set('session.cookie_httponly', '1');
        ini_set('session.cookie_samesite', 'Lax');
        ini_set('session.cookie_secure', '1');
        ini_set('session.gc_maxlifetime', '900');
        ini_set('session.serialize_handler', 'php_serialize');
        ini_set('session.use_strict_mode', '1');
    }

    /**
     * @throws SessionException
     */
    protected function setSessionHandler(\SessionHandlerInterface&\SessionIdInterface $sessionHandler): void
    {
        if (!session_set_save_handler($sessionHandler, true)) {
            throw new SessionException('Failed to set the session handler.');
        }
    }

    /**
     * @throws SessionException
     */
    protected function assertStarted(): void
    {
        if (!$this->isStarted()) {
            throw new SessionException('The session must be started.');
        }
    }
}
