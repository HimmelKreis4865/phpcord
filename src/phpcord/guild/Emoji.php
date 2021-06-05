<?php

namespace phpcord\guild;

use function is_null;

class Emoji {
	/** @var string $name */
	protected $name;
	
	/** @var string|null $id */
	protected $id;
	
	/** @var bool $animated */
	protected $animated = false;

	/**
	 * Emoji constructor.
	 *
	 * @param string $name
	 * @param string|null $id
	 * @param bool $animated
	 */
	public function __construct(string $name, ?string $id = null, bool $animated = false) {
		$this->name = $name;
		$this->id = $id;
		$this->animated = $animated;
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
	 * Returns whether the emoji is animated or not
	 *
	 * @warning Does not work for external things, this one will only be passed internal
	 *
	 * @api
	 *
	 * @return bool
	 */
	public function isAnimated(): bool {
		return $this->animated;
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
	
	public function asArray(): array {
		return [
			"name" => $this->getName(),
			"id" => $this->getId(),
			"animated" => $this->isAnimated()
		];
	}
}