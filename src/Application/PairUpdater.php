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
	 * @var StringHasherInterface;
	 */
	private $stringHasher;

	/**
	 * @param LoginsPasswordsRepositoryInterface $loginsPasswordsRepository
	 * @param StringHasherInterface $stringHasher
	 */
	public function __construct(
		LoginsPasswordsRepositoryInterface $loginsPasswordsRepository,
		StringHasherInterface $stringHasher
	)
	{
		$this->loginsPasswordsRepository = $loginsPasswordsRepository;
		$this->stringHasher = $stringHasher;
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
		$this->loginsPasswordsRepository->update($login,
			$this->stringHasher->hash($password)
		);
	}
}
