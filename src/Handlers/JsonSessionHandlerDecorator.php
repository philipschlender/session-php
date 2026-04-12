<?php

namespace Session\Handlers;

use Json\Exceptions\JsonException;
use Json\Services\JsonServiceInterface;

class JsonSessionHandlerDecorator extends SessionHandlerDecorator
{
    public function __construct(
        \SessionHandlerInterface&\SessionIdInterface $sessionHandler,
        private JsonServiceInterface $jsonService,
    ) {
        parent::__construct($sessionHandler);
    }

    public function read(string $id): string|false
    {
        $json = $this->sessionHandler->read($id);

        if ('' === $json) {
            return '';
        }

        if (false === $json) {
            return false;
        }

        try {
            $data = $this->jsonService->jsonToArray($json);
        } catch (JsonException $exception) {
            return false;
        }

        return serialize($data);
    }

    public function write(string $id, string $data): bool
    {
        $data = @unserialize($data);

        if (!is_array($data)) {
            return false;
        }

        try {
            $json = $this->jsonService->arrayToJson($data);
        } catch (JsonException $exception) {
            return false;
        }

        return $this->sessionHandler->write($id, $json);
    }
}
