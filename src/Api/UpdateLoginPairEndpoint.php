<?php

namespace Api;

use Adapters\Exceptions\LoginDoesNotExistException;
use Api\Exceptions\InvalidErrorCodeException;
use Api\Validators\LoginPasswordValidator;
use Application\PairUpdater;
use Slim\Http\Request;
use Slim\Http\Response;

class UpdateLoginPairEndpoint
{
	/**
	 * @var LoginPasswordValidator
	 */
	private $loginPasswordValidator;

	/**
	 * @var PairUpdater
	 */
	private $pairUpdater;

	/**
	 * @var ErrorsList
	 */
	private $errorsList;

	/**
	 * @param LoginPasswordValidator $loginPasswordValidator
	 * @param PairUpdater $pairUpdater
	 * @param ErrorsList $errorsList
	 */
	public function __construct(
		LoginPasswordValidator $loginPasswordValidator,
		PairUpdater $pairUpdater,
		ErrorsList $errorsList
	)
	{
		$this->loginPasswordValidator = $loginPasswordValidator;
		$this->pairUpdater = $pairUpdater;
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
		$errors = $this->loginPasswordValidator->isValid($request->getBody());
		if (empty($errors) === false) {
			return $this->getFailedResponse(
				$response,
				$errors
			);
		}

		$json = json_decode($request->getBody(), true);

		try {
			$this->pairUpdater->update($json['login'], $json['password']);
		}
		catch (LoginDoesNotExistException $exception)
		{
			return $this->getFailedResponse(
				$response,
				[ 'login' => [ ErrorsList::LOGIN_DOES_NOT_EXIST_IN_DATABASE ] ]
			);
		}

		return $response->withJson([
			'status' => 'success'
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
