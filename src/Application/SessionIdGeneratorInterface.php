<?php

namespace Application;

interface SessionIdGeneratorInterface
{
	/**
	 * @return string
	 */
	public function generateSessionId() : string;
}
