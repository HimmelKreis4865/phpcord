<?php

/*
 *         .__                                       .___
 * ______  |  |__  ______    ____   ____ _______   __| _/
 * \____ \ |  |  \ \____ \ _/ ___\ /  _ \\_  __ \ / __ |
 * |  |_> >|   Y  \|  |_> >\  \___(  <_> )|  | \// /_/ |
 * |   __/ |___|  /|   __/  \___  >\____/ |__|   \____ |
 * |__|         \/ |__|         \/                    \/
 *
 *
 * This library is developed by HimmelKreis4865 © 2022
 *
 * https://github.com/HimmelKreis4865/phpcord
 */

namespace phpcord\interaction\slash\options;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

class SubCommandOption extends SlashCommandOption {
	
	/**
	 * @param string $name
	 * @param string $description
	 * @param bool $required
	 * @param SlashCommandOption[] $options
	 */
	#[Pure] public function __construct(string $name, string $description, bool $required = false, private array $options = []) {
		parent::__construct($name, $description, $required);
	}
	
	public function getType(): int {
		return SlashCommandOptionTypes::SUB_COMMAND();
	}
	
	#[ArrayShape(['options' => "\phpcord\interaction\slash\options\SlashCommandOption[]"])] public function serializeOther(): array {
		return [
			'options' => $this->options
		];
	}
}