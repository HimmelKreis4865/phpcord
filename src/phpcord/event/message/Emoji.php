<?php

namespace phpcord\event\message;

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
	 * @return string|null
	 */
	public function getId(): ?string {
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}
	
	public function __toString(): string {
		return $this->name . (!is_null($this->id) ? ":" . $this->id : "");
	}
}


