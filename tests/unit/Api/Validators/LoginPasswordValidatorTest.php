<?php

namespace Api\Validators;

use Api\ErrorsList;
use PHPUnit\Framework\TestCase;

class LoginPasswordValidatorTest extends TestCase
{
	/**
	 * @dataProvider dataProvider
	 */
	public function test_isValid($body, $expectedErrors)
	{
		$validator = new LoginPasswordValidator();
		$errors = $validator->isValid($body);
		$this->assertEquals($expectedErrors, $errors);
	}

	public function dataProvider()
	{
		return [
			[
				'body' => '',
				'errors' => [
					'json' => [ ErrorsList::INCORRECT_JSON ],
				],
			],
			[
				'body' => 'abc',
				'errors' => [
					'json' => [ ErrorsList::INCORRECT_JSON ],
				],
			],
			[
				'body' => '{}',
				'errors' => [
					'login' => [ ErrorsList::VALUE_IS_REQUIRED ],
					'password' => [ ErrorsList::VALUE_IS_REQUIRED ],
				],
			],
			[
				'body' => '{"login":"", "password":""}',
				'errors' => [
					'login' => [ ErrorsList::LOGIN_CAN_NOT_BE_EMPTY ],
				],
			],
			[
				'body' => '{"login":"a", "password":""}',
				'errors' => [],
			],
			[
				'body' => '{"login":"123", "password":"abc"}',
				'errors' => [],
			],
		];
	}
}
