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

use phpcord\image\Dimension;
use phpcord\utils\Utils;

class MessageAttachment {
	
	/**
	 * @param int $id
	 * @param string $filename
	 * @param string|null $description
	 * @param string|null $contentType
	 * @param int $size
	 * @param Dimension|null $dimension
	 * @param string $url
	 * @param string $proxyUrl
	 * @param bool $ephemeral
	 */
	public function __construct(private int $id, private string $filename, private ?string $description, private ?string $contentType, private int $size, private ?Dimension $dimension, private string $url, private string $proxyUrl, private bool $ephemeral = false) { }
	
	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
	}
	
	/**
	 * @return string|null
	 */
	public function getDescription(): ?string {
		return $this->description;
	}
	
	/**
	 * @return string|null
	 */
	public function getContentType(): ?string {
		return $this->contentType;
	}
	
	/**
	 * @return Dimension|null
	 */
	public function getDimension(): ?Dimension {
		return $this->dimension;
	}
	
	/**
	 * @return string
	 */
	public function getFilename(): string {
		return $this->filename;
	}
	
	/**
	 * @return string
	 */
	public function getProxyUrl(): string {
		return $this->proxyUrl;
	}
	
	/**
	 * @return int
	 */
	public function getSize(): int {
		return $this->size;
	}
	
	/**
	 * @return string
	 */
	public function getUrl(): string {
		return $this->url;
	}
	
	/**
	 * @return bool
	 */
	public function isEphemeral(): bool {
		return $this->ephemeral;
	}
	
	public static function fromArray(array $array): ?MessageAttachment {
		if (!Utils::contains($array, 'id', 'filename', 'size', 'url')) return null;
		return new MessageAttachment($array['id'], $array['filename'], @$array['description'], @$array['content_type'], $array['size'], (@$array['width'] ? new Dimension($array['width'], $array['height']) : null), $array['url'], $array['proxy_url'] ?? $array['url'], $array['ephemeral'] ?? false);
	}
}