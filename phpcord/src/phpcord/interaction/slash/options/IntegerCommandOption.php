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
use phpcord\interaction\slash\SlashCommandChoice;

class IntegerCommandOption extends SlashCommandOption {
	
	/** @var SlashCommandChoice[] $choices */
	private array $choices;
	
	/**
	 * @param string $name
	 * @param string $description
	 * @param bool $required
	 * @param int|null $min
	 * @param int|null $max
	 * @param array $choices
	 * @param bool $autocomplete todo: implement this
	 */
	#[Pure] public function __construct(string $name, string $description, bool $required = false, private ?int $min = null, private ?int $max = null, array $choices = [], private bool $autocomplete = false) {
		parent::__construct($name, $description, $required);
		$this->choices = SlashCommandChoice::createOptionArray($choices);
	}
	
	public function getType(): int {
		return SlashCommandOptionTypes::INTEGER();
	}
	
	#[ArrayShape(['min_value' => "int|null", 'max_value' => "int|null", 'choices' => "array"])] public function serializeOther(): array {
		return [
			'min_value' => $this->min,
			'max_value' => $this->max,
			'choices' => $this->choices
		];
	}
}