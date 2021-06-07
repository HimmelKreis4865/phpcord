<?php

namespace phpcord\utils;

use phpcord\utils\theme\ThemeStorage;
use function str_replace;
use const PHP_EOL;

class MainLogger {
	
	/*
	 * the console can display 512 colors (256 foreground, 256 background)
	 * we no longer have the minecraft format to support as much colors as possible
	 * also, this is now case sensitive
	 *
	 * see this image for a color code list https://user-images.githubusercontent.com/89590/40762008-687f909a-646c-11e8-88d6-e268a064be4c.png
	 *
	 * the colors are having a color scheme, until the next color is called in comment, all colors below are from the same kind
	 * background colors are equivalent to foreground's
	 *
	 * => background colors are disabled yet due to incompatible reset types
	 */
	
	public const COLOR_FORMATS = [
		"a" => 88,  // red
		"b" => 124,
		"c" => 160,
		"d" => 9,
		"e" => 196,
		"f" => 210,
		"g" => 198,
		"h" => 205,
		"i" => 202, // orange
		"j" => 208,
		"k" => 166,
		"l" => 178,
		"m" => 185, // yellow
		"n" => 190,
		"o" => 192,
		"p" => 214,
		"q" => 220,
		"r" => 226,
		"s" => 154,
		"t" => 118, // green
		"u" => 83,
		"v" => 82,
		"w" => 40,
		"x" => 41,
		"y" => 43,
		"z" => 31, // turquoise
		"0" => 67,
		"1" => 69, // blue
		"2" => 51,
		"3" => 57,
		"4" => 90, // lila
		"5" => 135,
		"6" => 129,
		"7" => 231, // white
		"8" => 230,
		"9" => 232, // black
		"A" => 238, // gray
		"B" => 241,
		"C" => 244,
		"D" => 247,
		"E" => 252,
		"F" => 254,
	];
	
	/**
	 * Logs an info to the terminal
	 *
	 * @api
	 *
	 * @param string $info
	 */
	public static function logInfo(string $info) {
		self::log(ThemeStorage::getInstance()->getTheme()->getInfoFormat($info));
	}
	/**
	 * Logs an info to the terminal
	 *
	 * @api
	 *
	 * @param string $warning
	 */
	public static function logWarning(string $warning) {
		self::log(ThemeStorage::getInstance()->getTheme()->getWarningFormat($warning));
	}
	
	/**
	 * Logs a warning to the terminal
	 *
	 * @api
	 *
	 * @param string $error
	 */
	public static function logError(string $error) {
		self::log(ThemeStorage::getInstance()->getTheme()->getErrorFormat($error));
	}
	
	/**
	 * Logs an error to the terminal
	 *
	 * @api
	 *
	 * @param string $emergency
	 */
	public static function logEmergency(string $emergency) {
		self::log(ThemeStorage::getInstance()->getTheme()->getEmergencyFormat($emergency));
	}
	
	/**
	 * Logs a notice to the terminal
	 *
	 * @api
	 *
	 * @param string $notice
	 */
	public static function logNotice(string $notice) {
		self::log(ThemeStorage::getInstance()->getTheme()->getNoticeFormat($notice));
	}
	
	/**
	 * Logs a debug to the terminal
	 *
	 * @api
	 *
	 * @param string $debug
	 */
	public static function logDebug(string $debug) {
		self::log(ThemeStorage::getInstance()->getTheme()->getDebugFormat($debug));
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
		
		echo self::convertColored($message . self::resetFormat()) . PHP_EOL;
	}
	
	/**
	 * Returns the formatted date
	 *
	 * @internal
	 *
	 * @return string
	 */
	private static function dateFormat(): string {
		return ThemeStorage::getInstance()->getTheme()->getDateFormat(date("H"), date("i"), date("s"), self::getMilliseconds());
	}
	
	protected static function resetFormat(): string {
		return  ThemeStorage::getInstance()->getTheme()->getResetFormat();
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
		foreach (self::COLOR_FORMATS as $key => $color) {
			$prefix = "\033[38;5;";
			$message = str_replace("§" . $key, $prefix . $color . "m", $message);
		}
		return $message;
	}
}