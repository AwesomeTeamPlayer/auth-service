<?php

namespace Application;

use Adapters\Exceptions\LoginAlreadyExistsException;
use Adapters\LoginsPasswordsRepositoryInterface;

class PairCreator
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
	 * @throws LoginAlreadyExistsException
	 */
	public function create(string $login, string $password)
	{
		$this->loginsPasswordsRepository->create($login, $password);
	}
}
