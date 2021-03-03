<?php

namespace phpcord\command\slash;

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