<?php

namespace Application;

use Adapters\Exceptions\LoginDoesNotExistException;
use Adapters\LoginsPasswordsRepositoryInterface;

class LoginChecker
{
	/**
	 * @var LoginsPasswordsRepositoryInterface
	 */
	private $loginsPasswordsRepository;

	/**
	 * @param LoginsPasswordsRepositoryInterface $loginsPasswordsRepository
	 */
	public function __construct(
		LoginsPasswordsRepositoryInterface $loginsPasswordsRepository
	)
	{
		$this->loginsPasswordsRepository = $loginsPasswordsRepository;
	}

	/**
	 * @param string $login
	 *
	 * @return bool
	 */
	public function checkLogin(string $login) : bool
	{
		try {
			$this->loginsPasswordsRepository->getHashedPassword($login);
		} catch (LoginDoesNotExistException $exception) {
			return false;
		}

		return true;
	}
}
