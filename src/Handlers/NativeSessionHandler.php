<?php

namespace Session\Handlers;

use Session\Exceptions\SessionException;
use Uuid\Exceptions\UuidException;
use Uuid\Factories\UuidFactoryInterface;

class NativeSessionHandler extends \SessionHandler
{
    public function __construct(
        protected UuidFactoryInterface $uuidFactory,
    ) {
    }

    /**
     * @throws SessionException
     */
    public function create_sid(): string
    {
        try {
            return $this->uuidFactory->createUuid()->toString();
        } catch (UuidException $exception) {
            throw new SessionException($exception->getMessage(), 0, $exception);
        }
    }
}
