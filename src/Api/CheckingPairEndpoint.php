<?php

namespace Api;

use Api\Exceptions\InvalidErrorCodeException;
use Api\Validators\LoginValidator;
use Application\LoginChecker;
use Slim\Http\Request;
use Slim\Http\Response;

class CheckingPairEndpoint
{
	/**
	 * @var LoginValidator
	 */
	private $loginValidator;

	/**
	 * @var LoginChecker
	 */
	private $loginChecker;

	/**
	 * @var ErrorsList
	 */
	private $errorsList;

	/**
	 * @param LoginValidator $loginValidator
	 * @param LoginChecker $loginChecker
	 * @param ErrorsList $errorsList
	 */
	public function __construct(
		LoginValidator $loginValidator,
		LoginChecker $loginChecker,
		ErrorsList $errorsList
	)
	{
		$this->loginValidator = $loginValidator;
		$this->loginChecker = $loginChecker;
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
		$errors = $this->loginValidator->isValid($request->getBody());
		if (empty($errors) === false) {
			return $this->getFailedResponse(
				$response,
				$errors
			);
		}

		$json = json_decode($request->getBody(), true);

		$hasLogin = $this->loginChecker->checkLogin($json['login']);

		return $response->withJson([
			'hasLogin' => $hasLogin
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
