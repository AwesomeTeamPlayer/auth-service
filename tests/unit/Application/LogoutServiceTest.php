<?php

namespace Application;

use Adapters\Exceptions\SessionIdDoesNotExistException;
use Adapters\SessionsRepositoryInterface;
use PHPUnit\Framework\TestCase;

class LogoutServiceTest extends TestCase
{
	public function test_logout()
	{
		$sessionsRepository = $this->getMockBuilder(SessionsRepositoryInterface::class)
			->getMock();

		$sessionsRepository->method('remove')->willReturn('user\'s login value');

		$logoutService = new LogoutService(
			$sessionsRepository
		);

		$result = $logoutService->logout('sessionId');
		$this->assertTrue($result);
	}

	public function test_logout_when_session_id_does_not_exist()
	{
		$sessionsRepository = $this->getMockBuilder(SessionsRepositoryInterface::class)
			->getMock();

		$sessionsRepository->method('remove')->willThrowException(new SessionIdDoesNotExistException());

		$logoutService = new LogoutService(
			$sessionsRepository
		);

		$result = $logoutService->logout('sessionId');
		$this->assertFalse($result);
	}
}
