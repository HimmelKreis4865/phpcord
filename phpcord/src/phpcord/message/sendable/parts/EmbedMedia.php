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

class EmbedMedia implements JsonSerializable {
	
	/**
	 * @param string $url An url to an image or attachment
	 * @param string|null $proxyUrl
	 * @param int|null $height
	 * @param int|null $width
	 */
	public function __construct(private string $url, private ?string $proxyUrl = null, private ?int $height = null, private ?int $width = null) { }
	
	#[ArrayShape(['url' => "string", 'proxy_url' => "null|string", 'height' => "int|null", 'width' => "int|null"])]
	public function jsonSerialize(): array {
		return [
			'url' => $this->url,
			'proxy_url' => $this->proxyUrl,
			'height' => $this->height,
			'width' => $this->width
		];
	}
}