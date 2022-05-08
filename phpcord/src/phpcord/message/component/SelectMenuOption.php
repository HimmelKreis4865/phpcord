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

namespace phpcord\message\component;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use JsonSerializable;
use phpcord\message\PartialEmoji;

class SelectMenuOption implements JsonSerializable {
	
	/** @var bool $default */
	private bool $default = false;
	
	/**
	 * @param string $name
	 * @param string $displayName
	 * @param string|null $description
	 * @param PartialEmoji|null $emoji
	 */
	public function __construct(private string $name, private string $displayName, private ?string $description = null, private ?PartialEmoji $emoji = null) { }
	
	/**
	 * @return SelectMenuOption
	 */
	public function default(): SelectMenuOption {
		$this->default = true;
		return $this;
	}
	
	/**
	 * @param string $name
	 * @param string $displayName
	 * @param string|null $description
	 * @param PartialEmoji|null $emoji
	 *
	 * @return SelectMenuOption
	 */
	#[Pure] public static function new(string $name, string $displayName, ?string $description = null, ?PartialEmoji $emoji = null): SelectMenuOption {
		return new SelectMenuOption($name, $displayName, $description, $emoji);
	}
	
	#[ArrayShape(['label' => "string", 'value' => "string", 'description' => "null|string", 'emoji' => "null|\phpcord\message\PartialEmoji", 'default' => "bool"])]
	public function jsonSerialize(): array {
		return [
			'label' => $this->name,
			'value' => $this->displayName,
			'description' => $this->description,
			'emoji' => $this->emoji,
			'default' => $this->default
		];
	}
}