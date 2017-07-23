<?php

namespace tests\helpers\Application;

use Application\StringHasherInterface;

class EmptyStringHasher implements StringHasherInterface
{

	/**
	 * @param string $text
	 *
	 * @return string
	 */
	public function hash(string $text): string
	{
		return $text;
	}
}
