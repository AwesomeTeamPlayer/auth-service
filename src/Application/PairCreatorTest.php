<?php

namespace Application;

use Adapters\Exceptions\LoginAlreadyExistsException;
use Adapters\LoginsPasswordsRepositoryInterface;
use PHPUnit\Framework\TestCase;

class PairCreatorTest extends TestCase
{
	public function test_create_with_success()
	{
		$repository = $this->getMockBuilder(LoginsPasswordsRepositoryInterface::class)
			->setMethods(['create', 'update', 'getPassword'])
			->getMock();

		$pairCreator = new PairCreator($repository);
		$pairCreator->create('login', 'password');

		$this->assertTrue(true);
	}

	/**
	 * @expectedException \Adapters\Exceptions\LoginAlreadyExistsException
	 */
	public function test_create_with_LoginAlreadyExistsException()
	{
		$repository = $this->getMockBuilder(LoginsPasswordsRepositoryInterface::class)
			->setMethods(['create', 'update', 'getPassword'])
			->getMock();
		$repository->method('create')->willThrowException(new LoginAlreadyExistsException());

		$pairCreator = new PairCreator($repository);
		$pairCreator->create('login', 'password');
	}
}
