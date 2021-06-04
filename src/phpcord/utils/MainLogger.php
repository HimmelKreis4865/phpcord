<?php

namespace phpcord\utils;

use phpcord\Discord;
use function array_keys;
use function array_merge;
use function array_values;
use function str_replace;
use const PHP_EOL;

class MainLogger {
	/** @var string[] includes all available terminal colors */
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

	/** @var array includes all color codes under another minecraft's color format */
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
	
	/**
	 * Logs an info to the terminal
	 *
	 * @api
	 *
	 * @param string $info
	 */
	public static function logInfo(string $info) {
		self::log("§r[INFO]: " . $info);
	}
	/**
	 * Logs an info to the terminal
	 *
	 * @api
	 *
	 * @param string $warning
	 */
	public static function logWarning(string $warning) {
		self::log("§e[WARNING]: " . $warning);
	}
	
	/**
	 * Logs a warning to the terminal
	 *
	 * @api
	 *
	 * @param string $error
	 */
	public static function logError(string $error) {
		self::log("§4[ERROR]: " . $error);
	}
	
	/**
	 * Logs an error to the terminal
	 *
	 * @api
	 *
	 * @param string $emergency
	 */
	public static function logEmergency(string $emergency) {
		self::log("§5[EMERGENCY]: " . $emergency);
	}
	
	/**
	 * Logs a notice to the terminal
	 *
	 * @api
	 *
	 * @param string $notice
	 */
	public static function logNotice(string $notice) {
		self::log("§b[NOTICE]: " . $notice);
	}
	
	/**
	 * Logs a debug to the terminal
	 *
	 * @api
	 *
	 * @param string $debug
	 */
	public static function logDebug(string $debug) {
		self::logInfo($debug);
		//if (Discord::getInstance()->debugMode) self::log("§7[DEBUG]: " . $debug);
	}
	
	/**
	 * Logs a message with dateformat and converted colors
	 *
	 * @api
	 *
	 * @param string $message
	 */
	public static function log(string $message) {
		$message = self::dateFormat() . " " . $message;
		LogStore::addMessage(self::removeColors($message));
		
		echo self::convertColored($message) . self::TERMINAL_COLORS["RESET"] . PHP_EOL;
	}
	
	/**
	 * Returns the formatted date
	 *
	 * @internal
	 *
	 * @return string
	 */
	private static function dateFormat(): string {
		return "§6[" . date("H:i:s.") . self::getMilliseconds() . "]";
	}
	
	/**
	 * Returns milliseconds between 000-999
	 *
	 * @internal
	 *
	 * @return string
	 */
	protected static function getMilliseconds(): string {
		$time = strval(intval(((float) (explode(" ", microtime())[0]) * 1000)));

		$time = (strlen($time) === 2 ? $time = "0" . $time : (strlen($time) === 1 ? $time = "00" . $time : $time));

		return $time;
	}
	
	/**
	 * Converts all colors needed for the terminal
	 *
	 * @internal
	 *
	 * @param string $message
	 *
	 * @return string
	 */
	protected static function convertColored(string $message): string {
		foreach (self::COLOR_UNITS as $key => $color) {
			$message = str_replace($key, $color, $message);
		}
		return $message;
	}
	
	/**
	 * Removes all colors of a string
	 *
	 * @api
	 *
	 * @param string $message
	 *
	 * @return string
	 */
	public static function removeColors(string $message): string {
		return str_replace(array_merge(array_keys(self::COLOR_UNITS), array_values(self::TERMINAL_COLORS)), "", $message);
	}
}