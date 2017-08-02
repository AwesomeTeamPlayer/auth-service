<?php

namespace Api;

use Adapters\MysqlLoginsPasswordsRepository;
use Adapters\MysqlSessionRepository;
use Api\Validators\LoginPasswordValidator;
use Api\Validators\LoginValidator;
use Api\Validators\SessionIdValidator;
use Application\LoginChecker;
use Application\LoginService;
use Application\LogoutService;
use Application\PairCreator;
use Application\PairUpdater;
use Application\SessionIdGenerator;
use Application\Sha1StringHasher;
use AwesomeTeamPlayer\Libraries\Adapters\EventsRepositoryInterface;
use AwesomeTeamPlayer\Libraries\Adapters\RabbitMqEventsRepository;
use mysqli;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Slim\App;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

class ApplicationBuilder
{
	/**
	 * @param ApplicationConfig $applicationConfig
	 *
	 * @return App
	 */
	public function build(ApplicationConfig $applicationConfig) : App
	{
		$mysqli = $this->getMysqli($applicationConfig);
		$amqp = $this->getAmqpStreamConnection($applicationConfig);

		$app = new App(new Container(
			[
				'settings' => [
					'displayErrorDetails' => true,
				],
			]
		));

		$loginsPasswordsRepository = new MysqlLoginsPasswordsRepository($mysqli);
		$sessionRepository = new MysqlSessionRepository($mysqli);
		$eventsRepository = $this->buildEventRepository($applicationConfig);

		$createLoginPairEndpoint = new CreateLoginPairEndpoint(
			new LoginPasswordValidator(),
			new PairCreator(
				$loginsPasswordsRepository,
				new Sha1StringHasher()
			),
			new ErrorsListToTextualConverter()
		);

		$updateLoginPairEndpoint = new UpdateLoginPairEndpoint(
			new LoginPasswordValidator(),
			new PairUpdater(
				$loginsPasswordsRepository,
				new Sha1StringHasher()
			),
			new ErrorsListToTextualConverter()
		);

		$loginEndpoint = new LoginEndpoint(
			new LoginPasswordValidator(),
			new LoginService(
				$loginsPasswordsRepository,
				$eventsRepository,
				$sessionRepository,
				new Sha1StringHasher(),
				new SessionIdGenerator()
			),
			new ErrorsListToTextualConverter()
		);

		$logoutEndpoint = new LogoutEndpoint(
			new SessionIdValidator(),
			new LogoutService(
				$sessionRepository
			),
			new ErrorsListToTextualConverter()
		);

		$checkingPairEndpoint = new CheckingPairEndpoint(
			new LoginValidator(),
			new LoginChecker(
				$loginsPasswordsRepository
			),
			new ErrorsListToTextualConverter()
		);

		$app->put('/pair', function (Request $request, Response $response) use ($createLoginPairEndpoint) {
			return $createLoginPairEndpoint->run($request, $response);
		});

		$app->post('/pair', function (Request $request, Response $response) use ($updateLoginPairEndpoint) {
			return $updateLoginPairEndpoint->run($request, $response);
		});

		$app->post('/login', function (Request $request, Response $response) use ($loginEndpoint) {
			return $loginEndpoint->run($request, $response);
		});

		$app->post('/logout', function (Request $request, Response $response) use ($logoutEndpoint) {
			return $logoutEndpoint->run($request, $response);
		});

		$app->post('/has-login', function (Request $request, Response $response) use ($checkingPairEndpoint) {
			return $checkingPairEndpoint->run($request, $response);
		});

		$app->get('/', function (Request $request, Response $response) use ($applicationConfig, $mysqli, $amqp) {
			return $response->withJson(
				[
					'type' => 'auth-service',
					'config' => $applicationConfig->getArray(),
					'status' => [
						'is_connected'=> [
							'MySQL' => $mysqli->ping(),
							'RabbitMQ' => $amqp->isConnected(),
						],
					],
				]
			);
		});

		return $app;
	}

	/**
	 * @param ApplicationConfig $applicationConfig
	 *
	 * @return mysqli
	 */
	private function getMysqli(ApplicationConfig $applicationConfig) : mysqli
	{
		return new mysqli(
			$applicationConfig->getMysqlHost(),
			$applicationConfig->getMysqlUser(),
			$applicationConfig->getMysqlPassword(),
			$applicationConfig->getMysqlDatabase(),
			$applicationConfig->getMysqlPort()
		);
	}

	/**
	 * @param ApplicationConfig $applicationConfig
	 *
	 * @return EventsRepositoryInterface
	 */
	private function buildEventRepository(ApplicationConfig $applicationConfig) : EventsRepositoryInterface
	{
		$connection = $this->getAmqpStreamConnection($applicationConfig);
		$channel = $connection->channel();
		$channel->queue_declare(
			$applicationConfig->getRabbitmqChannel(),
			false,
			false,
			false,
			false
		);

		return new RabbitMqEventsRepository(
			$channel,
			$applicationConfig->getRabbitmqChannel()
		);
	}

	/**
	 * @param ApplicationConfig $applicationConfig
	 *
	 * @return AMQPStreamConnection
	 */
	private function getAmqpStreamConnection(ApplicationConfig $applicationConfig) : AMQPStreamConnection
	{
		return new AMQPStreamConnection(
			$applicationConfig->getRabbitmqHost(),
			$applicationConfig->getRabbitmqPort(),
			$applicationConfig->getRabbitmqUser(),
			$applicationConfig->getRabbitmqPassword()
		);
	}
}
