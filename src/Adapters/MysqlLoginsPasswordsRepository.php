<?php

namespace Adapters;

use Adapters\Exceptions\LoginAlreadyExistsException;
use Adapters\Exceptions\LoginDoesNotExistException;
use mysqli;

class MysqlLoginsPasswordsRepository implements LoginsPasswordsRepositoryInterface
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
	 * @param string $password
	 *
	 * @return void
	 *
	 * @throws LoginAlreadyExistsException
	 */
	public function create(string $login, string $password)
	{
		$sqlQuery = "
			INSERT INTO login_password (login, password) VALUES ('" . $login . "', '" . $password . "');
		";

		if ($this->mysqli->query($sqlQuery) === false) {
			throw new LoginAlreadyExistsException();
		}
	}

	/**
	 * @param string $login
	 * @param string $password
	 *
	 * @return void
	 *
	 * @throws LoginDoesNotExistException
	 */
	public function update(string $login, string $password)
	{
		$this->getPassword($login);

		$sqlQuery = "
			UPDATE login_password SET password = '" . $password . "' WHERE login='" . $login . "';
		";

		$this->mysqli->query($sqlQuery);
	}

	/**
	 * @param string $login
	 *
	 * @return string
	 *
	 * @throws LoginDoesNotExistException
	 */
	public function getPassword(string $login): string
	{
		$sqlQuery = "
			SELECT * FROM login_password WHERE login='" . $login . "' LIMIT 1;
		";

		$results = $this->mysqli->query($sqlQuery);
		if ($results->num_rows === 0) {
			throw new LoginDoesNotExistException();
		}

		return (string) $results->fetch_assoc()['password'];
	}
}
