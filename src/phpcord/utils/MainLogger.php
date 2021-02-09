<?php

namespace phpcord\utils;

use phpcord\Discord;

class MainLogger {
	public const TERMINAL_COLORS = [
		"PURPUR" => "\033[35m",
		"DARK_RED" => "\033[31m",
		"RED" => "\033[91m",
		"PINK" => "\033[95m",
		"ORANGE" => "\033[33m",
		"YELLOW" => "\033[93m",
		"LIGHT_GREEN" => "\033[92m",
		"AQUA" => "\033[96m",
		"TURQUOISE" => "\033[36m",
		"GREEN" => "\033[32m",
		"DARK_BLUE" => "\033[34m",
		"LIGHT_GRAY" => "\033[37m",
		"DARK_GRAY" => "\033[90m",
		"LIGHT_BLUE" => "\033[94m",
		"WHITE" => "\033[30m",
		"RESET" => "\033[39m",
	];

	public const COLOR_UNITS = [
		"§a" => self::TERMINAL_COLORS["LIGHT_GREEN"],
		"§b" => self::TERMINAL_COLORS["AQUA"],
		"§c" => self::TERMINAL_COLORS["RED"],
		"§d" => self::TERMINAL_COLORS["PINK"],
		"§e" => self::TERMINAL_COLORS["YELLOW"],
		"§f" => self::TERMINAL_COLORS["WHITE"],
		"§r" => self::TERMINAL_COLORS["RESET"],
		"§1" => self::TERMINAL_COLORS["DARK_BLUE"],
		"§2" => self::TERMINAL_COLORS["GREEN"],
		"§3" => self::TERMINAL_COLORS["LIGHT_GREEN"],
		"§4" => self::TERMINAL_COLORS["DARK_RED"],
		"§5" => self::TERMINAL_COLORS["PURPUR"],
		"§6" => self::TERMINAL_COLORS["ORANGE"],
		"§7" => self::TERMINAL_COLORS["LIGHT_GRAY"],
		"§8" => self::TERMINAL_COLORS["DARK_GRAY"],
		"§9" => self::TERMINAL_COLORS["LIGHT_BLUE"],
	];

	public static function logInfo(string $info) {
		self::log(self::TERMINAL_COLORS["RESET"] . "[INFO]: " . $info);
	}

	public static function logWarning(string $warning) {
		self::log(self::TERMINAL_COLORS["YELLOW"] . "[WARNING]: " . $warning);
	}

	public static function logError(string $error) {
		self::log(self::TERMINAL_COLORS["DARK_RED"] . "[ERROR]: " . $error);
	}

	public static function logEmergency(string $emergency) {
		self::log(self::TERMINAL_COLORS["DARK_RED"] . "[EMERGENCY]: " . $emergency);
	}

	public static function logNotice(string $notice) {
		self::log(self::TERMINAL_COLORS["LIGHT_BLUE"] . "[NOTICE]: " . $notice);
	}

	public static function logDebug(string $message) {
		if (Discord::$debugMode) self::log(self::TERMINAL_COLORS["LIGHT_GRAY"] . "[DEBUG]: " . $message);
	}

	public static function log(string $message) {
		echo self::dateFormat() . " " . self::convertColored($message) . self::TERMINAL_COLORS["RESET"] . PHP_EOL;
	}

	private static function dateFormat(): string {
		return self::TERMINAL_COLORS["TURQUOISE"] . "[" . date("H:i:s.") . self::getMilliseconds() . "]";
	}

	public static function getMilliseconds(): string {
		$time = strval(intval(((float) (explode(" ", microtime())[0]) * 1000)));

		$time = (strlen($time) === 2 ? $time = "0" . $time : (strlen($time) === 1 ? $time = "00" . $time : $time));

		return $time;
	}


	public static function convertColored(string $message): string {
		foreach (self::COLOR_UNITS as $key => $color) {
			$message = str_replace($key, $color, $message);
		}
		return $message;
	}

}


