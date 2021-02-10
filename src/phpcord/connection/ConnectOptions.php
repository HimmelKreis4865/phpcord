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
	 * Returns the token for identifying
	 *
	 * @internal
	 *
	 * @return string
	 */
	public function getToken(): string {
		return $this->token;
	}

	/**
	 * Returns the bitwise number of intents
	 *
	 * @internal
	 *
	 * @return int
	 */
	public function getIntents(): int {
		return $this->intents;
	}
}