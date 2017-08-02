<?php

namespace Application;

class Sha1StringHasher implements StringHasherInterface
{
	/**
	 * @param string $text
	 *
	 * @return string
	 */
	public function hash(string $text): string
	{
		return sha1($text);
	}
}
