<?php

namespace Adapters;

use mysqli;
use PHPUnit\Framework\TestCase;

class MysqlSessionRepositoryTest extends TestCase
{
	/**
	 * @var mysqli
	 */
	private $mysqli;

	public function setUp()
	{
		$this->mysqli = new mysqli('127.0.0.1', 'root', 'root', 'testdb', 13306);
		$this->mysqli->query('CREATE TABLE login_session (login varchar(255) NOT NULL, session_id VARCHAR(255) NOT NULL);');
	}
	public function tearDown()
	{
		$this->mysqli->query('DROP TABLE login_session;');
		$this->mysqli->close();
	}

	public function test_add_and_remove_when_repository_is_empty()
	{
		$repository = new MysqlSessionRepository($this->mysqli);
		$repository->add('login', 'sessionId');

		$login = $repository->remove('sessionId');
		$this->assertEquals('login', $login);
	}

	/**
	 * @expectedException \Adapters\Exceptions\SessionIdDoesNotExistException
	 */
	public function test_remove_when_login_does_not_exists()
	{
		$repository = new MysqlSessionRepository($this->mysqli);
		$repository->remove('sessionId_123456');
	}
}
