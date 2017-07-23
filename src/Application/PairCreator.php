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
	 * @throws LoginAlreadyExistsException
	 */
	public function create(string $login, string $password)
	{
		$this->loginsPasswordsRepository->create(
			$login,
			$this->stringHasher->hash($password)
		);
	}
}
