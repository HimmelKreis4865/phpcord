<?php

namespace phpcord\connection;

final class ConnectOptions implements Authorizable {
	/** @var string $token */
	private $token;
	/** @var int $intents */
	private $intents;

	/**
	 * ConnectOptions constructor.
	 *
	 * @param string $token
	 * @param int $intents
	 */
	public function __construct(string $token, int $intents) {
		$this->token = $token;
		$this->intents = $intents;
	}

	/**
	 * @return string
	 */
	public function getToken(): string {
		return $this->token;
	}

	/**
	 * @return int
	 */
	public function getIntents(): int {
		return $this->intents;
	}
}


