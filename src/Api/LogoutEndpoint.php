<?php

namespace Api;

use Api\Exceptions\InvalidErrorCodeException;
use Api\Validators\SessionIdValidator;
use Application\LogoutService;
use Slim\Http\Request;
use Slim\Http\Response;

class LogoutEndpoint extends AbstractEndpoint
{
	/**
	 * @var SessionIdValidator
	 */
	private $sessionIdValidator;

	/**
	 * @var LogoutService
	 */
	private $logoutService;

	/**
	 * @param SessionIdValidator $sessionIdValidator
	 * @param LogoutService $logoutService
	 * @param ErrorsListToTextualConverter $errorsListToTextualConverter
	 */
	public function __construct(
		SessionIdValidator $sessionIdValidator,
		LogoutService $logoutService,
		ErrorsListToTextualConverter $errorsListToTextualConverter
	)
	{
		parent::__construct($errorsListToTextualConverter);

		$this->sessionIdValidator = $sessionIdValidator;
		$this->logoutService = $logoutService;
	}

	/**
	 * @param Request $request
	 * @param Response $response
	 *
	 * @return Response
	 *
	 * @throws InvalidErrorCodeException
	 */
	public function run(Request $request, Response $response) : Response
	{
		$errors = $this->sessionIdValidator->isValid($request->getBody());
		if (empty($errors) === false) {
			return $this->getFailedResponse(
				$response,
				$errors
			);
		}

		$json = json_decode($request->getBody(), true);

		$isSuccess = $this->logoutService->logout($json['sessionId']);

		if ($isSuccess) {
			return $response->withJson([
				'status' => 'success'
			]);
		}

		return $response->withJson([
			'status' => 'failed'
		]);
	}
}
