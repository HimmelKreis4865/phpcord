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

use GdImage;
use JetBrains\PhpStorm\ArrayShape;
use phpcord\exception\FileNotFoundException;
use function basename;
use function file_exists;
use function file_get_contents;
use function imagepng;
use function mime_content_type;
use function ob_end_flush;
use function ob_get_contents;
use function ob_start;

final class Attachment {

	/**
	 * @param string $content
	 * @param string $mimeType
	 * @param string $filename
	 * @param string|null $description
	 */
	public function __construct(private string $content, private string $mimeType, private string $filename, private ?string $description = null) { }

	/**
	 * @return string
	 */
	public function getContent(): string {
		return $this->content;
	}

	/**
	 * @return string
	 */
	public function getMimeType(): string {
		return $this->mimeType;
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

	public static function fromImage(GdImage $image, string $fileName, string $description = null): Attachment {
		ob_start();
		imagepng($image);
		$content = ob_get_contents();
		ob_end_flush();
		return new Attachment($content, "image/png", $fileName, $description);
	}

	public static function fromFile(string $path, string $description = null, string $customFileName = null): Attachment {
		if (!file_exists($path))
			throw new FileNotFoundException("Path " . $path . " for attachment does not exist!");
		return new Attachment(file_get_contents($path), mime_content_type($path), $customFileName ?? basename($path), $description);
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