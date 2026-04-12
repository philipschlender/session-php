<?php

namespace Tests;

use Json\Exceptions\JsonException;
use Json\Services\JsonServiceInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Session\Handlers\JsonSessionHandlerDecorator;

class JsonSessionHandlerDecoratorTest extends TestCase
{
    protected MockObject&\SessionHandlerInterface&\SessionIdInterface $parentSessionHandler;

    protected MockObject&JsonServiceInterface $jsonService;

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

        $this->jsonService = $this->createMock(JsonServiceInterface::class);

        $this->sessionHandler = new JsonSessionHandlerDecorator($this->parentSessionHandler, $this->jsonService);
    }

    public function testRead(): void
    {
        $id = $this->fakerService->getDataTypeGenerator()->randomString();
        $data = [
            $this->fakerService->getDataTypeGenerator()->randomString(),
        ];
        $json = $this->fakerService->getDataTypeGenerator()->randomString();

        $this->parentSessionHandler->expects($this->once())
            ->method('read')
            ->with($id)
            ->willReturn($json);

        $this->jsonService->expects($this->once())
            ->method('jsonToArray')
            ->with($json)
            ->willReturn($data);

        $this->assertEquals(serialize($data), $this->sessionHandler->read($id));
    }

    public function testReadParentSessionHandlerReturnsEmptyString(): void
    {
        $id = $this->fakerService->getDataTypeGenerator()->randomString();

        $this->parentSessionHandler->expects($this->once())
            ->method('read')
            ->with($id)
            ->willReturn('');

        $this->jsonService->expects($this->never())
            ->method('jsonToArray');

        $this->assertEquals('', $this->sessionHandler->read($id));
    }

    public function testReadParentSessionHandlerReturnsFalse(): void
    {
        $id = $this->fakerService->getDataTypeGenerator()->randomString();

        $this->parentSessionHandler->expects($this->once())
            ->method('read')
            ->with($id)
            ->willReturn(false);

        $this->jsonService->expects($this->never())
            ->method('jsonToArray');

        $this->assertEquals(false, $this->sessionHandler->read($id));
    }

    public function testReadJsonServiceJsonToArrayThrowsException(): void
    {
        $id = $this->fakerService->getDataTypeGenerator()->randomString();

        $this->parentSessionHandler->expects($this->once())
            ->method('read')
            ->with($id)
            ->willReturn($this->fakerService->getDataTypeGenerator()->randomString());

        $this->jsonService->expects($this->once())
            ->method('jsonToArray')
            ->willThrowException(new JsonException('nope.'));

        $this->assertEquals(false, $this->sessionHandler->read($id));
    }

    public function testWrite(): void
    {
        $id = $this->fakerService->getDataTypeGenerator()->randomString();
        $data = [
            $this->fakerService->getDataTypeGenerator()->randomString(),
        ];
        $json = $this->fakerService->getDataTypeGenerator()->randomString();

        $this->jsonService->expects($this->once())
            ->method('arrayToJson')
            ->with($data)
            ->willReturn($json);

        $this->parentSessionHandler->expects($this->once())
            ->method('write')
            ->with($id, $json)
            ->willReturn(true);

        $this->assertTrue($this->sessionHandler->write($id, serialize($data)));
    }

    public function testWriteFailedToUnserializeData(): void
    {
        $this->jsonService->expects($this->never())
            ->method('arrayToJson');

        $this->parentSessionHandler->expects($this->never())
            ->method('write');

        $this->assertFalse(
            $this->sessionHandler->write(
                $this->fakerService->getDataTypeGenerator()->randomString(),
                $this->fakerService->getDataTypeGenerator()->randomString()
            )
        );
    }

    public function testWriteSerializedDataIsNotArray(): void
    {
        $this->jsonService->expects($this->never())
            ->method('arrayToJson');

        $this->parentSessionHandler->expects($this->never())
            ->method('write');

        $this->assertFalse(
            $this->sessionHandler->write(
                $this->fakerService->getDataTypeGenerator()->randomString(),
                serialize($this->fakerService->getDataTypeGenerator()->randomString())
            )
        );
    }

    public function testWriteJsonServiceArrayToJsonThrowsException(): void
    {
        $this->jsonService->expects($this->once())
            ->method('arrayToJson')
            ->willThrowException(new JsonException('nope.'));

        $this->parentSessionHandler->expects($this->never())
            ->method('write');

        $this->assertFalse(
            $this->sessionHandler->write(
                $this->fakerService->getDataTypeGenerator()->randomString(),
                serialize([
                    $this->fakerService->getDataTypeGenerator()->randomString(),
                ])
            )
        );
    }
}
