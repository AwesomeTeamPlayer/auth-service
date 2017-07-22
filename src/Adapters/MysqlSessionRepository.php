<?php

namespace Adapters;

use Adapters\Exceptions\SessionIdDoesNotExistException;
use mysqli;

class MysqlSessionRepository implements SessionsRepositoryInterface
{

	/**
	 * @var mysqli
	 */
	private $mysqli;

	/**
	 * @param mysqli $mysqli
	 */
	public function __construct(mysqli $mysqli)
	{
		$this->mysqli = $mysqli;
	}

	/**
	 * @param string $login
	 * @param string $sessionId
	 *
	 * @return void
	 */
	public function add(string $login, string $sessionId)
	{
		$sqlQuery = "
			INSERT INTO login_session (login, session_id) VALUES ('" . $login . "', '" . $sessionId . "');
		";

		$this->mysqli->query($sqlQuery);
	}

	/**
	 * @param string $sessionId
	 *
	 * @return string - login
	 *
	 * @throws SessionIdDoesNotExistException
	 */
	public function remove(string $sessionId): string
	{
		$sqlQuery = "
			SELECT * FROM login_session WHERE session_id='" . $sessionId . "';
		";

		$results = $this->mysqli->query($sqlQuery);
		if ($results->num_rows === 0) {
			throw new SessionIdDoesNotExistException();
		}

		$login = (string) $results->fetch_assoc()['login'];

		$sqlQuery = "
			DELETE FROM login_session WHERE session_id='" . $sessionId . "';
		";
		$this->mysqli->query($sqlQuery);

		return $login;
	}
}
