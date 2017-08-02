<?php

namespace Api;

use Adapters\EventsRepositoryInterface;
use Adapters\MysqlLoginsPasswordsRepository;
use Adapters\RabbitMqEventsRepository;
use Api\Validators\LoginPasswordValidator;
use Application\PairCreator;
use Application\Sha1StringHasher;
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

		$repository = new MysqlLoginsPasswordsRepository($mysqli);

		$createLoginPairEndpoint = new CreateLoginPairEndpoint(
			new LoginPasswordValidator(),
			new PairCreator(
				$repository,
				new Sha1StringHasher()
			),
			new ErrorsListToTextualConverter()
		);

		$app->put('/pair', function (Request $request, Response $response) use ($createLoginPairEndpoint) {
			return $createLoginPairEndpoint->run($request, $response);
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
