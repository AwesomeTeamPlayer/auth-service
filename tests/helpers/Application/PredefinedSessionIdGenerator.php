<?php

namespace tests\helpers\Application;

use Application\SessionIdGeneratorInterface;

class PredefinedSessionIdGenerator implements SessionIdGeneratorInterface
{
	/**
	 * @var string[]
	 */
	private $sessionsId = [];

	/**
	 * @var int
	 */
	private $currentIndex = 0;

	/**
	 * @param string[] $sessionsId
	 */
	public function __construct(array $sessionsId)
	{
		$this->sessionsId = $sessionsId;
	}

	/**
	 * @return string
	 */
	public function generateSessionId(): string
	{
		if ($this->currentIndex >= count($this->sessionsId)) {
			$this->currentIndex = 0;
		}

		return $this->sessionsId[$this->currentIndex++];
	}
}
