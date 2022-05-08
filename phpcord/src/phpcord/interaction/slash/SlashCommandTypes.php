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

namespace phpcord\interaction\slash;

use phpcord\utils\enum\EnumTrait;

/**
 * @method static int CHAT_INPUT()
 * @method static int USER()
 * @method static int MESSAGE()
 */
final class SlashCommandTypes {
	use EnumTrait;
	
	protected static function make(): void {
		self::register('CHAT_INPUT', 1);
		self::register('USER', 2);
		self::register('MESSAGE', 3);
	}
}