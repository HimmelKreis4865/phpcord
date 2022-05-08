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

namespace phpcord\message;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use JsonSerializable;
use function array_filter;
use function implode;
use function rawurlencode;
use function urlencode;

class PartialEmoji implements JsonSerializable {
	
	/**
	 * @param string|null $name
	 * @param int|null $id
	 * @param bool $animated
	 */
	public function __construct(private ?string $name, private ?int $id = null, private bool $animated = false) { }
	
	/**
	 * @return string|null
	 */
	public function getName(): ?string {
		return $this->name;
	}
	
	/**
	 * @return int|null
	 */
	public function getId(): ?int {
		return $this->id;
	}
	
	/**
	 * @return bool
	 */
	public function isAnimated(): bool {
		return $this->animated;
	}
	
	#[ArrayShape(['name' => "null|string", 'id' => "int|null", 'animated' => "bool"])]
	public function jsonSerialize(): array {
		return [
			'name' => $this->name,
			'id' => $this->id,
			'animated' => $this->animated
		];
	}
	
	public function encode(): string {
		return urlencode(implode(':', array_filter([$this->name, $this->id], fn($k) => $k)));
	}
	
	#[Pure] public static function fromArray(array $array): PartialEmoji {
		return new PartialEmoji(@$array['name'], @$array['id'], $array['animated'] ?? false);
	}
}