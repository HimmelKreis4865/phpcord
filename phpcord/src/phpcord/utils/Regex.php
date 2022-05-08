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

use function in_array;
use function preg_match;
use function preg_match_all;
use function preg_replace;
use function preg_split;

class Regex {
	
	/**
	 * @param string $string
	 * @param string $pattern
	 *
	 * @return array
	 */
	public static function split(string $string, string $pattern): array {
		return preg_split($pattern, $string);
	}
	
	/**
	 * @param string $string
	 * @param string $pattern
	 *
	 * @return bool
	 */
	public static function match(string $string, string $pattern): bool {
		return (preg_match($pattern, $string, $matches) === 1 and in_array($string, $matches, true));
	}
	
	/**
	 * @param string $string
	 * @param string $pattern
	 *
	 * @return array
	 */
	public static function matchAll(string $string, string $pattern): array {
		return (preg_match_all($pattern, $string, $matches) ? $matches[0] : []);
	}
	
	/**
	 * @param string $string
	 * @param string $pattern
	 * @param string $replacement
	 *
	 * @return string
	 */
	public static function replace(string $string, string $pattern, string $replacement): string {
		return preg_replace($pattern, $replacement, $string);
	}
}