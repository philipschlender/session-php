<?php

namespace Tests;

use PHPUnit\Framework\MockObject\MockObject;
use Session\Handlers\SessionHandlerDecorator;

class SessionHandlerDecoratorTest extends TestCase
{
    protected MockObject&\SessionHandlerInterface&\SessionIdInterface $parentSessionHandler;

    protected \SessionHandlerInterface&\SessionIdInterface $sessionHandler;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var MockObject&\SessionHandlerInterface&\SessionIdInterface $parentSessionHandler */
        $parentSessionHandler = $this->createMockForIntersectionOfInterfaces([
            \SessionHandlerInterface::class,
            \SessionIdInterface::class,
        ]);

        $this->parentSessionHandler = $parentSessionHandler;

        $this->sessionHandler = new class($this->parentSessionHandler) extends SessionHandlerDecorator {};
    }

    public function testClose(): void
    {
        $this->parentSessionHandler->expects($this->once())
            ->method('close')
            ->willReturn(true);

        $this->assertTrue($this->sessionHandler->close());
    }

    public function testDestroy(): void
    {
        $id = $this->fakerService->getDataTypeGenerator()->randomString();

        $this->parentSessionHandler->expects($this->once())
            ->method('destroy')
            ->with($id)
            ->willReturn(true);

        $this->assertTrue($this->sessionHandler->destroy($id));
    }

    public function testGc(): void
    {
        $max_lifetime = $this->fakerService->getDataTypeGenerator()->randomInteger();
        $numberOfDeletedSessions = $this->fakerService->getDataTypeGenerator()->randomInteger();

        $this->parentSessionHandler->expects($this->once())
            ->method('gc')
            ->with($max_lifetime)
            ->willReturn($numberOfDeletedSessions);

        $this->assertEquals($numberOfDeletedSessions, $this->sessionHandler->gc($max_lifetime));
    }

    public function testOpen(): void
    {
        $path = $this->fakerService->getDataTypeGenerator()->randomString();
        $name = $this->fakerService->getDataTypeGenerator()->randomString();

        $this->parentSessionHandler->expects($this->once())
            ->method('open')
            ->with($path, $name)
            ->willReturn(true);

        $this->assertTrue($this->sessionHandler->open($path, $name));
    }

    public function testRead(): void
    {
        $id = $this->fakerService->getDataTypeGenerator()->randomString();
        $data = $this->fakerService->getDataTypeGenerator()->randomString();

        $this->parentSessionHandler->expects($this->once())
            ->method('read')
            ->with($id)
            ->willReturn($data);

        $this->assertEquals($data, $this->sessionHandler->read($id));
    }

    public function testWrite(): void
    {
        $id = $this->fakerService->getDataTypeGenerator()->randomString();
        $data = $this->fakerService->getDataTypeGenerator()->randomString();

        $this->parentSessionHandler->expects($this->once())
            ->method('write')
            ->with($id, $data)
            ->willReturn(true);

        $this->assertTrue($this->sessionHandler->write($id, $data));
    }

    public function testCreateSid(): void
    {
        $sessionId = $this->fakerService->getDataTypeGenerator()->randomString();

        $this->parentSessionHandler->expects($this->once())
            ->method('create_sid')
            ->willReturn($sessionId);

        $this->assertEquals($sessionId, $this->sessionHandler->create_sid());
    }
}
