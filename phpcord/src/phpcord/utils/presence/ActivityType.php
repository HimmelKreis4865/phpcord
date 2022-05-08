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

namespace phpcord\utils\presence;

use phpcord\utils\enum\EnumTrait;

/**
 * @method static int GAME()
 * @method static int STREAMING()
 * @method static int LISTENING()
 * @method static int WATCHING()
 * @method static int CUSTOM()
 * @method static int COMPETING()
 */
final class ActivityType {
	use EnumTrait;
	
	protected static function make(): void {
		self::register('Game', 0);
		self::register('Streaming', 1);
		self::register('Listening', 2);
		self::register('Watching', 3);
		self::register('Custom', 4);
		self::register('Competing', 5);
	}
}