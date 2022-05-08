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

namespace phpcord\message\sendable;

use JetBrains\PhpStorm\ArrayShape;
use phpcord\image\ImageData;

final class Attachment {
	
	/**
	 * @param ImageData $imageData
	 * @param string $filename
	 * @param string|null $description
	 */
	public function __construct(private ImageData $imageData, private string $filename, private ?string $description = null) { }
	
	/**
	 * @return ImageData
	 */
	public function getImageData(): ImageData {
		return $this->imageData;
	}
	
	/**
	 * @return string
	 */
	public function getFilename(): string {
		return $this->filename;
	}
	
	/**
	 * @return string|null
	 */
	public function getDescription(): ?string {
		return $this->description;
	}
	
	#[ArrayShape(['id' => "int", 'description' => "null|string", 'filename' => "string"])]
	public function encode(int $id): array {
		return [
			'id' => $id,
			'description' => $this->description,
			'filename' => $this->filename
		];
	}
}