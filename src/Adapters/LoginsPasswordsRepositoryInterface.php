<?php

namespace Adapters;

use Adapters\Exceptions\LoginAlreadyExistsException;
use Adapters\Exceptions\LoginDoesNotExistException;

interface LoginsPasswordsRepositoryInterface
{
	/**
	 * @param string $login
	 * @param string $password
	 *
	 * @return void
	 *
	 * @throws LoginAlreadyExistsException
	 */
	public function create(string $login, string $password);

	/**
	 * @param string $login
	 * @param string $password
	 *
	 * @return void
	 *
	 * @throws LoginDoesNotExistException
	 */
	public function update(string $login, string $password);

	/**
	 * @param string $login
	 *
	 * @return string
	 *
	 * @throws LoginDoesNotExistException
	 */
	public function getPassword(string $login): string;
}
