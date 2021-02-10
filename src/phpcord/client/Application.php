<?php

namespace phpcord\client;

final class Application {
	/** @var string $id */
	protected $id;
	
	/** @var int $flags */
	protected $flags = 0;

	/**
	 * Application constructor.
	 *
	 * @param string $id
	 * @param int $flags
	 */
	public function __construct(string $id, int $flags = 0) {
		$this->id = $id;
		$this->flags = $flags;
	}

	/**
	 * Returns the API of the application
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getId(): string {
		return $this->id;
	}

	/**
	 * Returns the bitwise flags of the application
	 *
	 * @api
	 *
	 * @return int
	 */
	public function getFlags(): int {
		return $this->flags;
	}
}


