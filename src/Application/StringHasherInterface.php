<?php

namespace Application;

interface StringHasherInterface
{
	/**
	 * @param string $text
	 *
	 * @return string
	 */
	public function hash(string $text): string;
}
