<?php

namespace Application;

class SessionIdGenerator
{
	public function generateSessionId()
	{
		return md5(mktime() . rand(-9999999999, 9999999999));
	}
}
