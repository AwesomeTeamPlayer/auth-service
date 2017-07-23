<?php

namespace Application;

use Adapters\Exceptions\LoginDoesNotExistException;
use Adapters\LoginsPasswordsRepositoryInterface;
use PHPUnit\Framework\TestCase;

class LoginCheckerTest extends TestCase
{
	public function test_checkLogin_when_login_exists()
	{
		$repository = $this->getMockBuilder(LoginsPasswordsRepositoryInterface::class)
			->setMethods(['create', 'update', 'getPassword'])
			->getMock();
		$repository->method('getPassword')->willReturn('password');

		$pairCreator = new LoginChecker($repository);
		$result = $pairCreator->checkLogin('login');

		$this->assertTrue($result);
	}

	public function test_checkLogin_when_login_does_not_exists()
	{
		$repository = $this->getMockBuilder(LoginsPasswordsRepositoryInterface::class)
			->setMethods(['create', 'update', 'getPassword'])
			->getMock();
		$repository->method('getPassword')->willThrowException(new LoginDoesNotExistException());

		$pairCreator = new LoginChecker($repository);
		$result = $pairCreator->checkLogin('login');

		$this->assertFalse($result);
	}
}
