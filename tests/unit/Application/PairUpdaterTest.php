<?php

namespace Application;

use Adapters\Exceptions\LoginDoesNotExistException;
use Adapters\LoginsPasswordsRepositoryInterface;
use tests\helpers\Application\EmptyStringHasher;
use PHPUnit\Framework\TestCase;

class PairUpdaterTest extends TestCase
{
	public function test_create_with_success()
	{
		$repository = $this->getMockBuilder(LoginsPasswordsRepositoryInterface::class)
			->setMethods(['create', 'update', 'getHashedPassword'])
			->getMock();

		$pairCreator = new PairUpdater($repository, new EmptyStringHasher());
		$pairCreator->update('login', 'password');

		$this->assertTrue(true);
	}

	/**
	 * @expectedException \Adapters\Exceptions\LoginDoesNotExistException
	 */
	public function test_create_with_LoginAlreadyExistsException()
	{
		$repository = $this->getMockBuilder(LoginsPasswordsRepositoryInterface::class)
			->setMethods(['create', 'update', 'getHashedPassword'])
			->getMock();
		$repository->method('update')->willThrowException(new LoginDoesNotExistException());

		$pairCreator = new PairUpdater($repository, new EmptyStringHasher());
		$pairCreator->update('login', 'password');
	}
}
