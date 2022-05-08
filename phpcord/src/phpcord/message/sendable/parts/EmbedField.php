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

namespace phpcord\message\sendable\parts;

use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;

class EmbedField implements JsonSerializable {
	
	/**
	 * @param string $name
	 * @param string $value description
	 * @param bool $inline set to true to display the field in one line aligned with the others
	 */
	public function __construct(private string $name, private string $value, private bool $inline = false) { }
	
	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}
	
	/**
	 * @return string
	 */
	public function getValue(): string {
		return $this->value;
	}
	
	/**
	 * @return bool
	 */
	public function isInline(): bool {
		return $this->inline;
	}
	
	#[ArrayShape(['name' => "string", 'value' => "string", 'inline' => "bool"])]
	public function jsonSerialize(): array {
		return [
			'name' => $this->name,
			'value' => $this->value,
			'inline' => $this->inline
		];
	}
}