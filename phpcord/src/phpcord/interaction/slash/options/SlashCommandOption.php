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

use JsonSerializable;

abstract class SlashCommandOption implements JsonSerializable {
	
	/**
	 * @param string $name
	 * @param string $description
	 * @param bool $required
	 */
	public function __construct(private string $name, private string $description, private bool $required = false) { }
	
	/**
	 * One of @see SlashCommandOptionTypes
	 *
	 * @return int
	 */
	abstract public function getType(): int;
	
	public function jsonSerialize(): array {
		return ([
			'type' => $this->getType(),
			'name' => $this->name,
			'description' => $this->description,
			'required' => $this->required
		] + $this->serializeOther());
	}
	
	public function serializeOther(): array { return []; }
}