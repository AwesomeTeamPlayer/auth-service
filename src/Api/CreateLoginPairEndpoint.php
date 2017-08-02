<?php

namespace Api;

use Adapters\Exceptions\LoginAlreadyExistsException;
use Api\Exceptions\InvalidErrorCodeException;
use Api\Validators\LoginPasswordValidator;
use Application\PairCreator;
use Slim\Http\Request;
use Slim\Http\Response;

class CreateLoginPairEndpoint extends AbstractEndpoint
{
	/**
	 * @var LoginPasswordValidator
	 */
	private $loginPasswordValidator;

	/**
	 * @var PairCreator
	 */
	private $pairCreator;


	/**
	 * @param LoginPasswordValidator $loginPasswordValidator
	 * @param PairCreator $pairCreator
	 * @param ErrorsListToTextualConverter $errorsListToTextualConverter
	 */
	public function __construct(
		LoginPasswordValidator $loginPasswordValidator,
		PairCreator $pairCreator,
		ErrorsListToTextualConverter $errorsListToTextualConverter
	)
	{
		parent::__construct($errorsListToTextualConverter);

		$this->loginPasswordValidator = $loginPasswordValidator;
		$this->pairCreator = $pairCreator;
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
			$this->pairCreator->create($json['login'], $json['password']);
		}
		catch (LoginAlreadyExistsException $exception)
		{
			return $this->getFailedResponse(
				$response,
				[ 'login' => [ ErrorsList::LOGIN_EXIST_IN_DATABASE ] ]
			);
		}

		return $response->withJson([
			'status' => 'success'
		]);
	}
}
