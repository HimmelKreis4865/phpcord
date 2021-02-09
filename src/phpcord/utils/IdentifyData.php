<?php

namespace phpcord\utils;

class IdentifyData {
	/** @var string $token */
	public $token;
	/** @var int $intents */
	public $intents = 513;

	/**
	 * IdentifyData constructor.
	 *
	 * @param string $token
	 * @param int $intents
	 */
	public function __construct(string $token, int $intents = 513) {
		$this->token = $token;
		$this->intents = $intents;
	}
}


