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

class EmbedProvider implements JsonSerializable {
	
	/**
	 * @param string $name
	 * @param string|null $url
	 */
	public function __construct(private string $name, private ?string $url = null) { }
	
	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}
	
	/**
	 * @return string|null
	 */
	public function getUrl(): ?string {
		return $this->url;
	}
	
	#[ArrayShape(['name' => "string", 'url' => "null|string"])] public function jsonSerialize(): array {
		return [
			'name' => $this->name,
			'url' => $this->url
		];
	}
}