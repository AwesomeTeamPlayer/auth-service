<?php

namespace Application;

class SessionIdGenerator implements SessionIdGeneratorInterface
{
	/**
	 * @return string
	 */
	public function generateSessionId() : string
	{
		return md5(time() . rand(-9999999999, 9999999999));
	}
}
