<?php

namespace Session\Handlers;

abstract class SessionHandlerDecorator implements \SessionHandlerInterface, \SessionIdInterface
{
    public function __construct(protected \SessionHandlerInterface&\SessionIdInterface $sessionHandler)
    {
    }

    public function close(): bool
    {
        return $this->sessionHandler->close();
    }

    public function destroy(string $id): bool
    {
        return $this->sessionHandler->destroy($id);
    }

    public function gc(int $max_lifetime): int|false
    {
        return $this->sessionHandler->gc($max_lifetime);
    }

    public function open(string $path, string $name): bool
    {
        return $this->sessionHandler->open($path, $name);
    }

    public function read(string $id): string|false
    {
        return $this->sessionHandler->read($id);
    }

    public function write(string $id, string $data): bool
    {
        return $this->sessionHandler->write($id, $data);
    }

    public function create_sid(): string
    {
        return $this->sessionHandler->create_sid();
    }
}
