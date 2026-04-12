<?php

namespace Tests;

use PHPUnit\Framework\MockObject\MockObject;
use Session\Exceptions\SessionException;
use Session\Handlers\NativeSessionHandler;
use Uuid\Exceptions\UuidException;
use Uuid\Factories\UuidFactoryInterface;
use Uuid\Factories\UuidV4Factory;

class NativeSessionHandlerTest extends TestCase
{
    protected MockObject&UuidFactoryInterface $uuidFactory;

    protected \SessionHandlerInterface&\SessionIdInterface $sessionHandler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->uuidFactory = $this->createMock(UuidFactoryInterface::class);

        $this->sessionHandler = new NativeSessionHandler($this->uuidFactory);
    }

    public function testCreateSid(): void
    {
        $uuidFactory = new UuidV4Factory();
        $uuid = $uuidFactory->createUuid();

        $this->uuidFactory->expects($this->once())
            ->method('createUuid')
            ->willReturn($uuid);

        $this->assertEquals($uuid->toString(), $this->sessionHandler->create_sid());
    }

    public function testCreateSidUuidFactoryCreateUuidThrowsException(): void
    {
        $this->expectException(SessionException::class);
        $this->expectExceptionMessage('nope.');

        $this->uuidFactory->expects($this->once())
            ->method('createUuid')
            ->willThrowException(new UuidException('nope.'));

        $this->sessionHandler->create_sid();
    }
}
