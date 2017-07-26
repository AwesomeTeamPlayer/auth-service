<?php

namespace Api;

use Adapters\Exceptions\LoginDoesNotExistException;
use Api\Validators\ErrorsList;
use Api\Validators\LoginPasswordValidator;
use Application\Exceptions\GivenPasswordDoesNotMatchToStoredPasswordException;
use Application\LoginService;
use Slim\Http\Request;
use Slim\Http\Response;

class LoginEndpoint
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
	 * @param Request $request
	 * @param Response $response
	 *
	 * @return Response
	 */
	public function run(Request $request, Response $response) : Response
	{
		$errors = $this->loginPasswordValidator->isValid($request->getBody());
		if (empty($errors) === false) {
			return $response->withJson($response);
		}

		$json = json_decode($request->getBody(), true);

		try {
			$sessionId = $this->loginService->login($json['login'], $json['password']);
		}
		catch (LoginDoesNotExistException $exception)
		{
			return $response->withJson([
				'status' => 'failed',
				'login' => [ ErrorsList::LOGIN_DOES_NOT_EXIST_IN_DATABASE ],
			]);
		}
		catch (GivenPasswordDoesNotMatchToStoredPasswordException $exception)
		{
			return $response->withJson([
				'status' => 'failed',
				'password' => [ ErrorsList::PASSWORD_IS_INCORRECT ],
			]);
		}

		return $response->withJson([
			'status' => 'success',
            'sessionId' => $sessionId,
		]);
	}
}
