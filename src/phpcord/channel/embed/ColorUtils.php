<?php

namespace phpcord\channel\embed;

use phpcord\channel\embed\components\RGB;
use function str_replace;
use function strtoupper;

final class ColorUtils {
	/**
	 * You can use some of the colors as example or for needed colors
	 */
	public const COLORS = [
		"WHITE" => 0xFFFFFF,
		"WHITESMOKE" => 0xF2F0E9,
		"WARM_WHITE" => 0xD6CDB4,
		"LIGHT_GRAY" => 0x8C8C8C,
		"GRAY" => 0x636363,
		"DARK_GRAY" => 0x454444,
		"LIGHT_BLACK" => 0x212020,
		"BLACK" => 0x000000,
		"LIGHT_MAGENTA" => 0xFFB3B3,
		"LIGHT_RED" => 0xD47B74,
		"RED" => 0xFF1300,
		"DARK_RED" => 0x730800,
		"BROWN" => 0x730800,
		"LIGHT_BROWN" => 0x635352,
		"OLIVE_BROWN" => 0x635C52,
		"ORANGE" => 0xFF8000,
		"DARK_ORANGE" => 0xF57842,
		"YELLOW" => 0xFFFF00,
		"NEON_YELLOW" => 0xD0FF00,
		"LIGHT_GREEN" => 0x88FF00,
		"OLIVE_GREEN" => 0x2B6E2B,
		"TURQUOISE" => 0x00FFCC,
		"LIGHT_BLUE" => 0x00EAFF,
		"SKY_BLUE" => 0x00B3FF,
		"OCEAN_BLUE" => 0x2B5EC4,
		"BLUE" => 0x004FF,
		"BLUE_PURPLE" => 0x8400FF,
		"DARK_PURPLE" => 0x520773,
		"LIGHT_PURPLE" => 0xAC4AE0,
		"PURPLE" => 0xA600FF
	];
	/** @var int the static min value of a hex decimal */
	public const HEX_MIN = 0;
	/** @var int the max value of a hex decimal (16,7 Million colors => possible rgb combinations) */
	public const HEX_MAX = 16777215;

	/** @var int $valid_code */
	public $decimal;
	
	/**
	 * ColorUtils constructor.
	 *
	 * You shouldn't create an instance for your own usage @see ColorUtils::createFromCustomData() is what you need
	 *
	 * @param int $decimal
	 */
	public function __construct(int $decimal) {
		$this->decimal = $decimal;
	}

	/**
	 * Tries to create a @link ColorUtils instance from some data
	 *
	 * @internal
	 *
	 * @param array|int|string|ColorUtils|RGB $data
	 *
	 * @return ColorUtils
	 */
	public static function createFromCustomData($data): self {
		if (is_array($data)) {
			$rgb = RGB::fromArray($data);
			if ($rgb === null) return self::createBlack();
			return self::fromRgb($rgb) ?? self::createBlack();
		} else if (is_int($data)) {
			return self::fromInt($data) ?? self::createBlack();
		} else if (is_string($data)) {
			return self::fromString($data) ?? self::createBlack();
		} else if ($data instanceof ColorUtils) {
			return $data;
		} else if ($data instanceof RGB) {
			return new self(hexdec(str_replace("#", "", $data->toHex())));
		}
		return self::createBlack();
	}

	/**
	 * Returns the default color (black)
	 *
	 * @internal
	 *
	 * @return static
	 */
	public static function createBlack(): self {
		return new self(0);
	}

	/**
	 * Tries to create a ColorUtils instance from an array, null on failure
	 *
	 * @internal
	 *
	 * @param RGB $rgb
	 *
	 * @return static|null
	 */
	public static function fromRgb(RGB $rgb): ?self {
		$hex = $rgb->toHex();
		$val = hexdec(str_replace("#", "", $hex));
		if (!is_int($val)) return null;
		return new self($val);
	}

	/**
	 * Tries to create a ColorUtils instance from a string, either hex value, "random" or in the list of @see ColorUtils::COLORS
	 *
	 * @internal
	 *
	 * @param string $hex
	 *
	 * @return static|null
	 */
	public static function fromString(string $hex): ?self {
		if (strtolower($hex) === "random") return new self(RGB::RANDOM()->toHex());
		if (isset(self::COLORS[strtoupper($hex)])) return new self(self::COLORS[strtoupper($hex)]);
		if (strlen($hex) === 6 or (strlen($hex) === 7 and $hex[0] === "#")) {
			$hex = str_replace("#", "", $hex);
			if (!ctype_xdigit($hex)) return null;
			$val = hexdec(str_replace("#", "", $hex));
			if (!is_int($val)) return null;
			return new self($val);
		}
		return null;
	}

	/**
	 * Tries to create a ColorUtils instance from an int that signals a decimal hex value between 0 and 16777215
	 *
	 * @internal
	 *
	 * @param int $dec
	 *
	 * @return static|null
	 */
	public static function fromInt(int $dec): ?self {
		if ($dec >= self::HEX_MIN and $dec <= self::HEX_MAX) return new self($dec);
		return null;
	}
}