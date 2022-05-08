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

namespace phpcord\logger;

use phpcord\utils\enum\EnumTrait;

/**
 * @method static string DEFAULT()
 * @method static string BLACK()
 * @method static string RED()
 * @method static string GREEN()
 * @method static string YELLOW()
 * @method static string ORANGE()
 * @method static string LIGHTGREEN()
 * @method static string BLUE()
 * @method static string PURPLE()
 * @method static string CYAN()
 * @method static string WHITE()
 * @method static string GREY()
 */
final class ColorMap {
	use EnumTrait;
	
	protected static function make(): void {
		self::register("default", "\033[0m");
		self::register("black", "\033[30m");
		self::register("red", "\033[31m");
		self::register("green", "\033[32m");
		self::register("yellow", "\033[93m");
		self::register("orange", "\033[33m");
		self::register("lightgreen", "\033[92m");
		self::register("blue", "\033[34m");
		self::register("purple", "\033[35m");
		self::register("cyan", "\033[36m");
		self::register("white", "\033[98m");
		self::register("grey", "\033[37m");
	}
}