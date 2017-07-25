<?php

namespace Application;

use Adapters\Exceptions\LoginDoesNotExistException;
use Adapters\LoginsPasswordsRepositoryInterface;
use Adapters\SessionsRepositoryInterface;
use Application\Exceptions\GivenPasswordDoesNotMatchToStoredPasswordException;
use AwesomeTeamPlayer\Libraries\Adapters\EventsRepositoryInterface;
use AwesomeTeamPlayer\Libraries\Adapters\ValueObjects\Event;

class LoginService
{
	/**
	 * @var LoginsPasswordsRepositoryInterface
	 */
	private $loginsPasswordsRepository;

	/**
	 * @var EventsRepositoryInterface
	 */
	private $eventsRepository;

	/**
	 * @var SessionsRepositoryInterface
	 */
	private $sessionsRepository;

	/**
	 * @var StringHasherInterface
	 */
	private $stringHasher;

	/**
	 * @var SessionIdGeneratorInterface
	 */
	private $sessionIdGenerator;

	/**
	 * @param LoginsPasswordsRepositoryInterface $loginsPasswordsRepository
	 * @param EventsRepositoryInterface $eventsRepository
	 * @param SessionsRepositoryInterface $sessionsRepository
	 * @param StringHasherInterface $stringHasher
	 * @param SessionIdGeneratorInterface $sessionIdGenerator
	 */
	public function __construct(
		LoginsPasswordsRepositoryInterface $loginsPasswordsRepository,
		EventsRepositoryInterface $eventsRepository,
		SessionsRepositoryInterface $sessionsRepository,
		StringHasherInterface $stringHasher,
		SessionIdGeneratorInterface $sessionIdGenerator
	)
	{
		$this->loginsPasswordsRepository = $loginsPasswordsRepository;
		$this->eventsRepository = $eventsRepository;
		$this->sessionsRepository = $sessionsRepository;
		$this->stringHasher = $stringHasher;
		$this->sessionIdGenerator = $sessionIdGenerator;
	}

	/**
	 * @param string $login
	 * @param string $password
	 *
	 * @return string - session ID
	 *
	 * @throws LoginDoesNotExistException
	 * @throws GivenPasswordDoesNotMatchToStoredPasswordException
	 */
	public function login(string $login, string $password) : string
	{
		$storedHashedPassword = $this->loginsPasswordsRepository->getHashedPassword($login);
		$hashedPassword = $this->stringHasher->hash($password);
		if ($storedHashedPassword !== $hashedPassword) {
			throw new GivenPasswordDoesNotMatchToStoredPasswordException();
		}

		$sessionId = $this->sessionIdGenerator->generateSessionId();
		$this->sessionsRepository->add($login, $sessionId);
		$this->publishEvent($login, $sessionId);

		return $sessionId;
	}

	/**
	 * @param string $login
	 * @param string $sessionId
	 *
	 * @return void
	 */
	private function publishEvent(string $login, string $sessionId)
	{
		$this->eventsRepository->push(
			new Event(
				'LoggedUser',
				new \DateTime(),
				[
					'login' => $login,
					'sessionId' => $sessionId,
				]
			)
		);
	}
}
