<?php

namespace Api\Validators;

use Api\ErrorsList;
use PHPUnit\Framework\TestCase;

class LoginValidatorTest extends TestCase
{
	/**
	 * @dataProvider dataProvider
	 */
	public function test_isValid($body, $expectedErrors)
	{
		$validator = new LoginValidator();
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
				],
			],
			[
				'body' => '{"login":""}',
				'errors' => [
					'login' => [ ErrorsList::LOGIN_CAN_NOT_BE_EMPTY ],
				],
			],
			[
				'body' => '{"login":"123"}',
				'errors' => [],
			],
		];
	}
}
