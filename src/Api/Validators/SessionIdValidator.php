<?php

namespace Api\Validators;

class SessionIdValidator
{
	/**
	 * @param string $body
	 *
	 * @return array
	 */
	public function isValid(string $body) : array
	{
		$json = json_decode($body, true);
		if ($json === null) {
			return ['json' => [ ErrorsList::INCORRECT_JSON ]];
		}

		$errors = [];
		if (array_key_exists('sessionId', $json) === false) {
			$errors['sessionId'][] = ErrorsList::VALUE_IS_REQUIRED;
		} else if ($json['sessionId'] === '') {
			$errors['sessionId'][] = ErrorsList::SESSION_ID_CAN_NOT_BE_EMPTY;
		}

		return $errors;
	}
}
