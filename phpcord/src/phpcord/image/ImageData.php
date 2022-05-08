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

namespace phpcord\image;

use InvalidArgumentException;
use phpcord\exception\FileNotFoundException;
use function base64_encode;
use function file_exists;
use function file_get_contents;
use function in_array;
use function mime_content_type;
use function strtolower;

final class ImageData {
	
	private const SUPPORTED_MIME = ['image/png', 'image/jpeg', 'image/gif'];
	
	/**
	 * @param string $mime
	 * @param string $bytes
	 */
	private function __construct(private string $mime, private string $bytes) { }
	
	/**
	 * @param string $filename
	 *
	 * @return ImageData
	 */
	public static function fromFile(string $filename): ImageData {
		if (!file_exists($filename)) throw new FileNotFoundException($filename);
		if (!($mime = mime_content_type($filename)) or !in_array($mime, self::SUPPORTED_MIME, true)) throw new InvalidArgumentException('Mime type for ' . $filename . ' could not be specified or is not supported.');
		return new ImageData($mime, file_get_contents($filename));
	}
	
	/**
	 * @param string $mime
	 * @param string $bytes
	 *
	 * @return ImageData
	 */
	public static function fromBytes(string $mime, string $bytes): ImageData {
		if (!in_array(strtolower($mime), self::SUPPORTED_MIME, true)) throw new InvalidArgumentException('Mime type ' . $mime . ' is not supported');
		return new ImageData(strtolower($mime), $bytes);
	}
	
	/**
	 * @return string
	 */
	public function getBytes(): string {
		return $this->bytes;
	}
	
	/**
	 * @return string
	 */
	public function getMime(): string {
		return $this->mime;
	}
	
	public function encode(): string {
		return 'data:' . $this->mime . ';base64,' . base64_encode($this->bytes);
	}
}