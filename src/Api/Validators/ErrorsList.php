<?php

namespace Api\Validators;

class ErrorsList
{
	const INCORRECT_JSON = 99;

	const LOGIN_EXIST_IN_DATABASE = 100;

	const LOGIN_DOES_NOT_EXIST_IN_DATABASE = 101;

	const LOGIN_CAN_NOT_BE_EMPTY = 102;

	const PASSWORD_IS_INCORRECT = 103;

	const VALUE_IS_REQUIRED = 104;
}
