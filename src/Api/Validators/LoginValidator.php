<?php

namespace Api\Validators;

class LoginValidator
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
		if (array_key_exists('login', $json) === false) {
			$errors['login'][] = ErrorsList::VALUE_IS_REQUIRED;
		} else if ($json['login'] === '') {
			$errors['login'][] = ErrorsList::LOGIN_CAN_NOT_BE_EMPTY;
		}

		return $errors;
	}
}
