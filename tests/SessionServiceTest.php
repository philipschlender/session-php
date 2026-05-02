<?php

namespace Tests;

use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use PHPUnit\Framework\MockObject\MockObject;
use Session\Exceptions\SessionException;
use Session\Services\SessionService;
use Session\Services\SessionServiceInterface;

class SessionServiceTest extends TestCase
{
    protected MockObject&\SessionHandlerInterface&\SessionIdInterface $sessionHandler;

    protected SessionServiceInterface $sessionService;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var MockObject&\SessionHandlerInterface&\SessionIdInterface $sessionHandler */
        $sessionHandler = $this->createMockForIntersectionOfInterfaces([
            \SessionHandlerInterface::class,
            \SessionIdInterface::class,
        ]);

        $this->sessionHandler = $sessionHandler;

        $this->sessionService = new SessionService($this->sessionHandler);
    }

    #[AllowMockObjectsWithoutExpectations]
    public function testConstruct(): void
    {
        $this->assertEquals('1', ini_get('session.cookie_httponly'));
        $this->assertEquals('Lax', ini_get('session.cookie_samesite'));
        $this->assertEquals('1', ini_get('session.cookie_secure'));
        $this->assertEquals('900', ini_get('session.gc_maxlifetime'));
        $this->assertEquals('php_serialize', ini_get('session.serialize_handler'));
        $this->assertEquals('1', ini_get('session.use_strict_mode'));
    }

    public function testStartSession(): void
    {
        $this->markTestSkipped();
    }

    public function testStartSessionSessionAlreadyStarted(): void
    {
        $this->markTestSkipped();
    }

    #[AllowMockObjectsWithoutExpectations]
    public function testIsStarted(): void
    {
        $this->assertFalse($this->sessionService->isStarted());
    }

    public function testGetSessionId(): void
    {
        $this->markTestSkipped();
    }

    #[AllowMockObjectsWithoutExpectations]
    public function testGetSessionIdSessionNotStarted(): void
    {
        $this->expectException(SessionException::class);
        $this->expectExceptionMessage('The session must be started.');

        $this->sessionService->getSessionId();
    }

    public function testRegenerateSessionId(): void
    {
        $this->markTestSkipped();
    }

    #[AllowMockObjectsWithoutExpectations]
    public function testRegenerateSessionIdSessionNotStarted(): void
    {
        $this->expectException(SessionException::class);
        $this->expectExceptionMessage('The session must be started.');

        $this->sessionService->regenerateSessionId();
    }

    public function testGet(): void
    {
        $this->markTestSkipped();
    }

    #[AllowMockObjectsWithoutExpectations]
    public function testGetSessionNotStarted(): void
    {
        $this->expectException(SessionException::class);
        $this->expectExceptionMessage('The session must be started.');

        $this->sessionService->get($this->fakerService->getDataTypeGenerator()->randomString());
    }

    public function testGetFailedToGetValue(): void
    {
        $this->markTestSkipped();
    }

    public function testSet(): void
    {
        $this->markTestSkipped();
    }

    #[AllowMockObjectsWithoutExpectations]
    public function testSetSessionNotStarted(): void
    {
        $this->expectException(SessionException::class);
        $this->expectExceptionMessage('The session must be started.');

        $this->sessionService->set(
            $this->fakerService->getDataTypeGenerator()->randomString(),
            $this->fakerService->getDataTypeGenerator()->randomString()
        );
    }

    public function testDelete(): void
    {
        $this->markTestSkipped();
    }

    #[AllowMockObjectsWithoutExpectations]
    public function testDeleteSessionNotStarted(): void
    {
        $this->expectException(SessionException::class);
        $this->expectExceptionMessage('The session must be started.');

        $this->sessionService->delete($this->fakerService->getDataTypeGenerator()->randomString());
    }

    public function testClear(): void
    {
        $this->markTestSkipped();
    }

    #[AllowMockObjectsWithoutExpectations]
    public function testClearSessionNotStarted(): void
    {
        $this->expectException(SessionException::class);
        $this->expectExceptionMessage('The session must be started.');

        $this->sessionService->clear();
    }

    public function testHas(): void
    {
        $this->markTestSkipped();
    }

    #[AllowMockObjectsWithoutExpectations]
    public function testHasSessionNotStarted(): void
    {
        $this->expectException(SessionException::class);
        $this->expectExceptionMessage('The session must be started.');

        $this->sessionService->has($this->fakerService->getDataTypeGenerator()->randomString());
    }

    public function testDestroySession(): void
    {
        $this->markTestSkipped();
    }

    #[AllowMockObjectsWithoutExpectations]
    public function testDestroySessionSessionNotStarted(): void
    {
        $this->expectException(SessionException::class);
        $this->expectExceptionMessage('The session must be started.');

        $this->sessionService->destroySession();
    }
}
