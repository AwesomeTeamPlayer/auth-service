<?php

namespace Application;

use Adapters\Exceptions\LoginDoesNotExistException;
use Adapters\LoginsPasswordsRepositoryInterface;
use Adapters\SessionsRepositoryInterface;
use AwesomeTeamPlayer\Libraries\Adapters\EventsRepositoryInterface;
use AwesomeTeamPlayer\Libraries\Adapters\ValueObjects\Event;
use tests\helpers\Application\PredefinedSessionIdGenerator;
use PHPUnit\Framework\TestCase;
use tests\helpers\Application\EmptyStringHasher;

class LoginServiceTest extends TestCase
{
	/**
	 * @expectedException \Adapters\Exceptions\LoginDoesNotExistException
	 */
	public function test_login_when_login_does_not_exist()
	{
		$loginsPasswordsRepository = $this->getMockBuilder(LoginsPasswordsRepositoryInterface::class)
			->setMethods(['create', 'update', 'getHashedPassword'])
			->getMock();
		$loginsPasswordsRepository->method('getHashedPassword')->willThrowException(new LoginDoesNotExistException());

		$eventsRepository = $this->getMockBuilder(EventsRepositoryInterface::class)
			->getMock();

		$sessionRepository = $this->getMockBuilder(SessionsRepositoryInterface::class)
			->getMock();

		$loginService = new LoginService(
			$loginsPasswordsRepository,
			$eventsRepository,
			$sessionRepository,
			new EmptyStringHasher(),
			new SessionIdGenerator()
		);
		$loginService->login('login', 'password');
	}

	/**
	 * @expectedException \Application\Exceptions\GivenPasswordDoesNotMatchToStoredPasswordException
	 */
	public function test_login_when_given_password_does_not_match_to_stored_password()
	{
		$loginsPasswordsRepository = $this->getMockBuilder(LoginsPasswordsRepositoryInterface::class)
			->setMethods(['create', 'update', 'getHashedPassword'])
			->getMock();
		$loginsPasswordsRepository->method('getHashedPassword')->willReturn('xxx');

		$eventsRepository = $this->getMockBuilder(EventsRepositoryInterface::class)
			->getMock();

		$sessionRepository = $this->getMockBuilder(SessionsRepositoryInterface::class)
			->getMock();

		$loginService = new LoginService(
			$loginsPasswordsRepository,
			$eventsRepository,
			$sessionRepository,
			new EmptyStringHasher(),
			new SessionIdGenerator()
		);
		$loginService->login('login', 'password');
	}

	public function test_login_success()
	{
		$loginsPasswordsRepository = $this->getMockBuilder(LoginsPasswordsRepositoryInterface::class)
			->setMethods(['create', 'update', 'getHashedPassword'])
			->getMock();
		$loginsPasswordsRepository->method('getHashedPassword')->willReturn('password');

		$eventsRepository = $this->getMockBuilder(EventsRepositoryInterface::class)
			->setMethods(['push'])
			->getMock();
		$eventsRepository->method('push')->willReturnCallback(function(Event $event){
			$this->assertEquals('LoggedUser', $event->name());
			$this->assertEquals([
				'login' => 'login',
				'sessionId' => 'sessionId'
			], $event->data());
		});

		$sessionRepository = $this->getMockBuilder(SessionsRepositoryInterface::class)
			->getMock();
		$sessionRepository->method('add')->willReturnCallback(function($login, $sessionId)
		{
			$this->assertEquals('login', $login);
			$this->assertEquals('sessionId', $sessionId);
		});

		$loginService = new LoginService(
			$loginsPasswordsRepository,
			$eventsRepository,
			$sessionRepository,
			new EmptyStringHasher(),
			new PredefinedSessionIdGenerator(['sessionId'])
		);
		$sessionId = $loginService->login('login', 'password');
		$this->assertEquals('sessionId', $sessionId);
	}
}
