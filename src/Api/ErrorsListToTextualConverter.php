<?php

namespace Api;

use Api\Exceptions\InvalidErrorCodeException;

class ErrorsListToTextualConverter
{
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
			case ErrorsList::INCORRECT_JSON:
				return 'Json is incorrect';
			case ErrorsList::LOGIN_EXIST_IN_DATABASE:
				return 'Given Login already exists in the database';
			case ErrorsList::LOGIN_DOES_NOT_EXIST_IN_DATABASE:
				return 'Given Login does not exist in the database';
			case ErrorsList::LOGIN_CAN_NOT_BE_EMPTY:
				return 'The login can not be empty';
			case ErrorsList::PASSWORD_IS_INCORRECT:
				return 'Given password is incorrect';
			case ErrorsList::VALUE_IS_REQUIRED:
				return 'This value is required';
			case ErrorsList::SESSION_ID_CAN_NOT_BE_EMPTY:
				return 'SessionId value can not be empty';
		}

		throw new InvalidErrorCodeException();
	}
}
