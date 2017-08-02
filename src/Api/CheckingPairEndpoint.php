<?php

namespace Api;

use Api\Exceptions\InvalidErrorCodeException;
use Api\Validators\LoginValidator;
use Application\LoginChecker;
use Slim\Http\Request;
use Slim\Http\Response;

class CheckingPairEndpoint extends AbstractEndpoint
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
	 * @param LoginValidator $loginValidator
	 * @param LoginChecker $loginChecker
	 * @param ErrorsListToTextualConverter $errorsListToTextualConverter
	 */
	public function __construct(
		LoginValidator $loginValidator,
		LoginChecker $loginChecker,
		ErrorsListToTextualConverter $errorsListToTextualConverter
	)
	{
		parent::__construct($errorsListToTextualConverter);

		$this->loginValidator = $loginValidator;
		$this->loginChecker = $loginChecker;
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
}
