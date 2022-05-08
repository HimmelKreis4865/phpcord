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

class EmbedFooter implements JsonSerializable {
	
	/**
	 * @param string $text
	 * @param string|null $iconUrl This either be an icon URL or an attachment
	 * @param string|null $proxyUrl
	 */
	public function __construct(private string $text, private ?string $iconUrl = null, private ?string $proxyUrl = null) { }
	
	#[ArrayShape(['text' => "string", 'icon_url' => "null|string", 'proxy_icon_url' => "null|string"])]
	public function jsonSerialize(): array {
		return [
			'text' => $this->text,
			'icon_url' => $this->iconUrl,
			'proxy_icon_url' => $this->proxyUrl
		];
	}
}