<?php

namespace Application;

use Adapters\Exceptions\SessionIdDoesNotExistException;
use Adapters\SessionsRepositoryInterface;

class LogoutService
{
	/**
	 * @var SessionsRepositoryInterface
	 */
	private $sessionsRepository;

	/**
	 * @param SessionsRepositoryInterface $sessionsRepository
	 */
	public function __construct(
		SessionsRepositoryInterface $sessionsRepository
	)
	{
		$this->sessionsRepository = $sessionsRepository;
	}

	/**
	 * @param string $sessionId
	 *
	 * @return bool -   true if session was deleted
	 *                  false otherwise
	 */
	public function logout(string $sessionId) : bool
	{
		try {
			$this->sessionsRepository->remove(
				$sessionId
			);
		}
		catch (SessionIdDoesNotExistException $exception)
		{
			return false;
		}

		return true;
	}
}
