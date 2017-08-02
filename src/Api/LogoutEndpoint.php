<?php

namespace Api;

use Api\Exceptions\InvalidErrorCodeException;
use Api\Validators\SessionIdValidator;
use Application\LogoutService;
use Slim\Http\Request;
use Slim\Http\Response;

class LogoutEndpoint
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
	 * @var ErrorsList
	 */
	private $errorsList;

	/**
	 * @param SessionIdValidator $sessionIdValidator
	 * @param LogoutService $logoutService
	 * @param ErrorsList $errorsList
	 */
	public function __construct(
		SessionIdValidator $sessionIdValidator,
		LogoutService $logoutService,
		ErrorsList $errorsList
	)
	{
		$this->sessionIdValidator = $sessionIdValidator;
		$this->logoutService = $logoutService;
		$this->errorsList = $errorsList;
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

		return $response->withJson([
			'status' => $isSuccess
		]);
	}

	/**
	 * @param Response $response
	 * @param $errors
	 *
	 * @return Response
	 */
	private function getFailedResponse(Response $response, $errors) : Response
	{
		$jsonResponse = ['status' => 'failed'];

		foreach ($errors as $label => $errorsList)
		{
			$jsonResponse[$label] = $this->errorsList->getTextualErrors($errorsList);
		}

		return $response->withJson($jsonResponse);
	}
}
