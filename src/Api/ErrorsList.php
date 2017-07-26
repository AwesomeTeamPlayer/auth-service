<?php

namespace Api\Validators;

use Api\Exceptions\InvalidErrorCodeException;

class ErrorsList
{
	const INCORRECT_JSON = 99;

	const LOGIN_EXIST_IN_DATABASE = 100;

	const LOGIN_DOES_NOT_EXIST_IN_DATABASE = 101;

	const LOGIN_CAN_NOT_BE_EMPTY = 102;

	const PASSWORD_IS_INCORRECT = 103;

	const VALUE_IS_REQUIRED = 104;

	const SESSION_ID_CAN_NOT_BE_EMPTY = 105;

	/**
	 * @param int[] $listOfErrorsCode
	 *
	 * @return string[]
	 *
	 * @throws InvalidErrorCodeException
	 */
	public function getTextualErrors(array $listOfErrorsCode) : array
	{
		$listOfTextualErrors = [];

		foreach ($listOfErrorsCode as $key => $errorCode) {
			$listOfTextualErrors[] = [
				'codeId' => $errorCode,
				'text' => $this->getTextualError($errorCode),
			];

		}

		return $listOfTextualErrors;
	}

	/**
	 * @param int $errorCode
	 *
	 * @return string
	 *
	 * @throws InvalidErrorCodeException
	 */
	private function getTextualError(int $errorCode) : string
	{
		switch ($errorCode) {
			case self::INCORRECT_JSON:
				return 'Json is incorrect';
			case self::LOGIN_EXIST_IN_DATABASE:
				return 'Given Login already exists in the database';
			case self::LOGIN_DOES_NOT_EXIST_IN_DATABASE:
				return 'Given Login does not exist in the database';
			case self::LOGIN_CAN_NOT_BE_EMPTY:
				return 'The login can not be empty';
			case self::PASSWORD_IS_INCORRECT:
				return 'Given password is incorrect';
			case self::VALUE_IS_REQUIRED:
				return 'This value is required';
			case self::SESSION_ID_CAN_NOT_BE_EMPTY:
				return 'SessionId value can not be empty';
		}

		throw new InvalidErrorCodeException();
	}
}
