<?php

namespace Api;

use Adapters\Exceptions\LoginDoesNotExistException;
use Api\Exceptions\InvalidErrorCodeException;
use Api\Validators\LoginPasswordValidator;
use Application\Exceptions\GivenPasswordDoesNotMatchToStoredPasswordException;
use Application\LoginService;
use Slim\Http\Request;
use Slim\Http\Response;

class LoginEndpoint extends AbstractEndpoint
{
	/**
	 * @var LoginPasswordValidator
	 */
	private $loginPasswordValidator;

	/**
	 * @var LoginService
	 */
	private $loginService;

	/**
	 * @param LoginPasswordValidator $loginPasswordValidator
	 * @param LoginService $loginService
	 * @param ErrorsListToTextualConverter $errorsListToTextualConverter
	 */
	public function __construct(
		LoginPasswordValidator $loginPasswordValidator,
		LoginService $loginService,
		ErrorsListToTextualConverter $errorsListToTextualConverter
	)
	{
		parent::__construct($errorsListToTextualConverter);

		$this->loginPasswordValidator = $loginPasswordValidator;
		$this->loginService = $loginService;
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
			$sessionId = $this->loginService->login($json['login'], $json['password']);
		}
		catch (LoginDoesNotExistException $exception)
		{
			return $this->getFailedResponse(
				$response,
				[ 'login' => [ ErrorsList::LOGIN_DOES_NOT_EXIST_IN_DATABASE ] ]
			);
		}
		catch (GivenPasswordDoesNotMatchToStoredPasswordException $exception)
		{
			return $this->getFailedResponse(
				$response,
				[ 'password' => [ ErrorsList::PASSWORD_IS_INCORRECT ] ]
			);
		}

		return $response->withJson([
			'status' => 'success',
            'sessionId' => $sessionId,
		]);
	}

}
