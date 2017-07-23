<?php

namespace Application;

use Adapters\Exceptions\LoginAlreadyExistsException;
use Adapters\LoginsPasswordsRepositoryInterface;
use PHPUnit\Framework\TestCase;
use tests\helpers\Application\EmptyStringHasher;

class PairCreatorTest extends TestCase
{
	public function test_create_with_success()
	{
		$repository = $this->getMockBuilder(LoginsPasswordsRepositoryInterface::class)
			->setMethods(['create', 'update', 'getHashedPassword'])
			->getMock();

		$pairCreator = new PairCreator($repository, new EmptyStringHasher());
		$pairCreator->create('login', 'password');

		$this->assertTrue(true);
	}

	/**
	 * @expectedException \Adapters\Exceptions\LoginAlreadyExistsException
	 */
	public function test_create_with_LoginAlreadyExistsException()
	{
		$repository = $this->getMockBuilder(LoginsPasswordsRepositoryInterface::class)
			->setMethods(['create', 'update', 'getHashedPassword'])
			->getMock();
		$repository->method('create')->willThrowException(new LoginAlreadyExistsException());

		$pairCreator = new PairCreator($repository, new EmptyStringHasher());
		$pairCreator->create('login', 'password');
	}
}
