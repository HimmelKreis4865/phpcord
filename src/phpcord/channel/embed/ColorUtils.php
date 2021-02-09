<?php

namespace phpcord\channel\embed;

use phpcord\channel\embed\components\RGB;
use function str_replace;
use function strtoupper;
use function var_dump;

final class ColorUtils {

	public const HEX_MIN = 0;
	public const HEX_MAX = 16777215;

	/** @var int $valid_code */
	public $decimal;

	public function __construct(int $decimal) {
		$this->decimal = $decimal;
	}

	/**
	 * @param array|int|string $data
	 * @return $this|null
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

	public static function createBlack(): self {
		return new self(0);
	}

	public static function fromRgb(RGB $rgb): ?self {
		$hex = $rgb->toHex();
		$val = hexdec(str_replace("#", "", $hex));
		var_dump($val);
		if (!is_int($val)) return null;
		return new self($val);
	}

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

	public static function fromInt(int $dec): ?self {
		if ($dec >= self::HEX_MIN and $dec <= self::HEX_MAX) return new self($dec);
		return null;
	}

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
}


