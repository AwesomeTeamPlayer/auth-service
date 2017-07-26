<?php

namespace Adapters;

use mysqli;
use PHPUnit\Framework\TestCase;

class MysqlLoginsPasswordsRepositoryTest extends TestCase
{
	/**
	 * @var mysqli
	 */
	private $mysqli;

	public function setUp()
	{
		$this->mysqli = new mysqli('127.0.0.1', 'root', 'root', 'testdb', 13306);
		$this->mysqli->query('CREATE TABLE login_password (login varchar(255) NOT NULL, password VARCHAR(255) NOT NULL);');
		$this->mysqli->query('CREATE UNIQUE INDEX login_password_unique_index ON login_password (login);');
	}
	public function tearDown()
	{
		$this->mysqli->query('DROP TABLE login_password;');
		$this->mysqli->close();
	}

	public function test_create_and_getPassword_on_empty_repository()
	{
		$repository = new MysqlLoginsPasswordsRepository($this->mysqli);
		$repository->create('login', 'password');

		$result = $repository->getHashedPassword('login');
		$this->assertEquals('password', $result);
	}

	/**
	 * @expectedException \Adapters\Exceptions\LoginAlreadyExistsException
	 */
	public function test_create_when_login_is_already_created()
	{
		$repository = new MysqlLoginsPasswordsRepository($this->mysqli);
		$repository->create('login', 'password');
		$repository->create('login', 'password');
	}

	public function test_update()
	{
		$repository = new MysqlLoginsPasswordsRepository($this->mysqli);
		$repository->create('login', 'password');
		$repository->update('login', 'new_password');

		$result = $repository->getHashedPassword('login');
		$this->assertEquals('new_password', $result);
	}

	/**
	 * @expectedException \Adapters\Exceptions\LoginDoesNotExistException
	 */
	public function test_update_when_login_does_not_exist()
	{
		$repository = new MysqlLoginsPasswordsRepository($this->mysqli);
		$repository->update('login', 'new_password');
	}

	/**
	 * @expectedException \Adapters\Exceptions\LoginDoesNotExistException
	 */
	public function test_getPassword_when_login_does_not_exist()
	{
		$repository = new MysqlLoginsPasswordsRepository($this->mysqli);
		$repository->getHashedPassword('login');
	}
}
