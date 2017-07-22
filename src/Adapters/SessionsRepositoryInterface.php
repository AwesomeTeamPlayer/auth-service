<?php

namespace Adapters;

use Adapters\Exceptions\SessionIdDoesNotExistException;

interface SessionsRepositoryInterface
{
	/**
	 * @param string $login
	 * @param string $sessionId
	 *
	 * @return void
	 */
	public function add(string $login, string $sessionId);

	/**
	 * @param string $sessionId
	 *
	 * @return string - login
	 *
	 * @throws SessionIdDoesNotExistException
	 */
	public function remove(string $sessionId): string;
}
