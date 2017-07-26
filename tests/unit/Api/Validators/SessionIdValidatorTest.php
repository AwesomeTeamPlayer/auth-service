<?php

namespace Api\Validators;

use Api\ErrorsList;
use PHPUnit\Framework\TestCase;

class SessionIdValidatorTest extends TestCase
{
	/**
	 * @dataProvider dataProvider
	 */
	public function test_isValid($body, $expectedErrors)
	{
		$validator = new SessionIdValidator();
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
					'sessionId' => [ ErrorsList::VALUE_IS_REQUIRED ],
				],
			],
			[
				'body' => '{"sessionId":""}',
				'errors' => [
					'sessionId' => [ ErrorsList::SESSION_ID_CAN_NOT_BE_EMPTY ],
				],
			],
			[
				'body' => '{"sessionId":"123"}',
				'errors' => [],
			],
		];
	}
}
