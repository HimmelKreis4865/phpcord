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

namespace phpcord\utils;

use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;
use OutOfBoundsException;
use function hexdec;
use function trim;

final class Color {
	
	private const RGB_COLOR_LIMIT = 16777215;
	
	private function __construct(private int $red, private int $green, private int $blue) { }
	
	/**
	 * @return int
	 */
	public function getRed(): int {
		return $this->red;
	}
	
	/**
	 * @return int
	 */
	public function getGreen(): int {
		return $this->green;
	}
	
	/**
	 * @return int
	 */
	public function getBlue(): int {
		return $this->blue;
	}
	
	#[Pure] public function hex(bool $addLeadingHashtag = false): string {
		return ($addLeadingHashtag ? '#' : '') . dechex($this->dec());
	}
	
	public function dec(): int {
		return (($this->red << 16) | ($this->green << 8) | $this->blue);
	}
	
	#[Pure] public function equals(Color $color): bool {
		return ($this->dec() === $color->dec());
	}
	
	/**
	 * @param string $hex
	 *
	 * @return Color
	 */
	public static function fromHex(string $hex): Color {
		$hex = trim($hex, '#');
		if (!Regex::match($hex, '/[a-fA-F0-9]{1,6}/')) throw new InvalidArgumentException('Hex code ' . $hex . ' is invalid!');
		return Color::fromInt(hexdec($hex));
	}
	
	/**
	 * @param int $decimal
	 *
	 * @return Color
	 */
	public static function fromInt(int $decimal): Color {
		if ($decimal < 0 or $decimal > self::RGB_COLOR_LIMIT) throw new OutOfBoundsException('Decimal ' . $decimal . ' is out of bounds (0-' . self::RGB_COLOR_LIMIT . ')');
		return new Color((($decimal >> 16) & 0xff), (($decimal >> 8) & 0xff), $decimal & 0xff);
	}
	
	/**
	 * @param int $red
	 * @param int $green
	 * @param int $blue
	 *
	 * @return Color
	 */
	public static function fromRgb(int $red, int $green, int $blue): Color {
		if ($red < 0 or $red > 255 or $green < 0 or $green > 255 or $blue < 0 or $blue > 255) throw new OutOfBoundsException('RGB code ' . $red . ', ' . $green . ', ' . $blue . ' is out of bounds.');
		return new Color($red, $green, $blue);
	}
	
	public function __debugInfo(): ?array {
		return [
			'color' => 'Red ' . $this->red . ', Green ' . $this->green . ', Blue ' . $this->blue
		];
	}
}