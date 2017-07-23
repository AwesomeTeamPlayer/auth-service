<?php

namespace Application;

use PHPUnit\Framework\TestCase;

class SessionIdGeneratorTest extends TestCase
{
	public function test_generateSessionId()
	{
		$generator = new SessionIdGenerator();
		$sessionId = $generator->generateSessionId();

		$this->assertTrue(strlen($sessionId) > 20);
	}
}
