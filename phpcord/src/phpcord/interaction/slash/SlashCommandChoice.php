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

namespace phpcord\interaction\slash;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use JsonSerializable;
use function is_scalar;
use function is_string;

class SlashCommandChoice implements JsonSerializable {
	
	/**
	 * @param string $name
	 * @param float|int|string $value
	 */
	public function __construct(private string $name, private float|int|string $value) { }
	
	/**
	 * @internal
	 *
	 * @param array $array
	 *
	 * @return SlashCommandChoice[]
	 */
	#[Pure] public static function createOptionArray(array $array): array {
		$options = [];
		foreach ($array as $k => $v) {
			if ($v instanceof SlashCommandChoice) {
				$options[] = $v;
				continue;
			}
			if (!is_scalar($v) or !is_string($k)) continue;
			$options[] = new SlashCommandChoice($k, $v);
		}
		return $options;
	}
	
	#[ArrayShape(['name' => "string", 'value' => "float|int|string"])] public function jsonSerialize(): array {
		return [
			'name' => $this->name,
			'value' => $this->value
		];
	}
}