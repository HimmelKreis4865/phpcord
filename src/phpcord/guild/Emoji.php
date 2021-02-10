<?php

namespace phpcord\guild;

use function is_null;

final class Emoji {
	/** @var string $name */
	protected $name;
	
	/** @var string|null $id */
	protected $id;

	/**
	 * Emoji constructor.
	 *
	 * @param string $name
	 * @param string|null $id
	 */
	public function __construct(string $name, ?string $id = null) {
		$this->name = $name;
		$this->id = $id;
	}

	/**
	 * ID might be null if Emoji is a default emoji
	 *
	 * @api
	 *
	 * @return string|null
	 */
	public function getId(): ?string {
		return $this->id;
	}

	/**
	 * Returns the name of the emoji, should never be null
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}
	
	/**
	 * Converts this emoji to a better formatted string that can be used to communicate with RESTAPI
	 *
	 * @internal
	 *
	 * @return string
	 */
	public function __toString(): string {
		return $this->name . (!is_null($this->id) ? ":" . $this->id : "");
	}
}