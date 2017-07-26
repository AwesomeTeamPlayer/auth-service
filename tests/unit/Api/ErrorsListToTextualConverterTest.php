<?php

namespace Api;

use PHPUnit\Framework\TestCase;

class ErrorsListToTextualConverterTest extends TestCase
{
	/**
	 * @dataProvider dataProvider
	 */
	public function test_correct_error_codes($listOfErrorsCode, $expectedListOfTextualErrors)
	{
		$errorsList = new ErrorsListToTextualConverter();
		$listOfTextualErrors = $errorsList->getTextualErrors($listOfErrorsCode);

		$this->assertEquals($listOfTextualErrors, $expectedListOfTextualErrors);
	}

	public function dataProvider()
	{
		return [
			[
				[ ErrorsList::INCORRECT_JSON ],
				[[ 'codeId' => ErrorsList::INCORRECT_JSON, 'text' => 'Json is incorrect' ]]
			],
			[
				[ ErrorsList::LOGIN_EXIST_IN_DATABASE ],
				[[ 'codeId' => ErrorsList::LOGIN_EXIST_IN_DATABASE, 'text' => 'Given Login already exists in the database' ]]
			],
			[
				[ ErrorsList::LOGIN_DOES_NOT_EXIST_IN_DATABASE ],
				[[ 'codeId' => ErrorsList::LOGIN_DOES_NOT_EXIST_IN_DATABASE, 'text' => 'Given Login does not exist in the database' ]]
			],
			[
				[ ErrorsList::LOGIN_CAN_NOT_BE_EMPTY ],
				[[ 'codeId' => ErrorsList::LOGIN_CAN_NOT_BE_EMPTY, 'text' => 'The login can not be empty' ]]
			],
			[
				[ ErrorsList::PASSWORD_IS_INCORRECT ],
				[[ 'codeId' => ErrorsList::PASSWORD_IS_INCORRECT, 'text' => 'Given password is incorrect' ]]
			],
			[
				[ ErrorsList::VALUE_IS_REQUIRED ],
				[[ 'codeId' => ErrorsList::VALUE_IS_REQUIRED, 'text' => 'This value is required' ]]
			],
			[
				[ ErrorsList::SESSION_ID_CAN_NOT_BE_EMPTY ],
				[[ 'codeId' => ErrorsList::SESSION_ID_CAN_NOT_BE_EMPTY, 'text' => 'SessionId value can not be empty' ]]
			],
			[
				[ ErrorsList::SESSION_ID_CAN_NOT_BE_EMPTY, ErrorsList::INCORRECT_JSON],
				[
					[ 'codeId' => ErrorsList::SESSION_ID_CAN_NOT_BE_EMPTY, 'text' => 'SessionId value can not be empty' ],
					[ 'codeId' => ErrorsList::INCORRECT_JSON, 'text' => 'Json is incorrect' ],
				]
			],
		];
	}
}
