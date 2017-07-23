<?php

namespace Application;

use Adapters\Exceptions\LoginDoesNotExistException;
use Adapters\LoginsPasswordsRepositoryInterface;

class PairUpdater
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
	 * @param string $password
	 *
	 * @return void
	 *
	 * @throws LoginDoesNotExistException
	 */
	public function update(string $login, string $password)
	{
		$this->loginsPasswordsRepository->update($login, $password);
	}
}
