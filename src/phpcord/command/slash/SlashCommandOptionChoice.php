<?php

namespace phpcord\command\slash;

use function is_int;
use function is_string;

final class SlashCommandOptionChoice {
	/** @var string $name */
	public $name;
	
	/** @var int|string $value */
	public $value;
	
	/**
	 * SlashCommandOptionChoice constructor.
	 *
	 * @param string $name
	 * @param string|int $value
	 */
	public function __construct(string $name, $value) {
		if (!is_string($value) and !is_int($value)) throw new \InvalidArgumentException("Cannot use $value as a SlashCommandOptionChoice! Please only use string or int");
		$this->name = $name;
		$this->value = $value;
	}
	
	public function encode(): array {
		return [
			"name" => $this->name,
			"value" => $this->value
		];
	}
}