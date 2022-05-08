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

class EmbedAuthor implements JsonSerializable {
	
	/**
	 * @param string $name
	 * @param string|null $url
	 * @param string|null $iconUrl An url or attachment
	 * @param string|null $proxyIconUrl
	 */
	public function __construct(private string $name, private ?string $url = null, private ?string $iconUrl = null, private ?string $proxyIconUrl = null) { }
	
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
	
	/**
	 * @return string|null
	 */
	public function getIconUrl(): ?string {
		return $this->iconUrl;
	}
	
	/**
	 * @return string|null
	 */
	public function getProxyIconUrl(): ?string {
		return $this->proxyIconUrl;
	}
	
	#[ArrayShape(['name' => "string", 'url' => "null|string", 'icon_url' => "null|string", 'proxy_icon_url' => "null|string"])]
	public function jsonSerialize(): array {
		return [
			'name' => $this->name,
			'url' => $this->url,
			'icon_url' => $this->iconUrl,
			'proxy_icon_url' => $this->proxyIconUrl
		];
	}
}