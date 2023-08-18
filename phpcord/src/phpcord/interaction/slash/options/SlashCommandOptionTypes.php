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

namespace phpcord\interaction\slash\options;

use phpcord\utils\enum\EnumTrait;

/**
 * @method static int SUB_COMMAND()
 * @method static int SUB_COMMAND_GROUP()
 * @method static int STRING()
 * @method static int INTEGER()
 * @method static int BOOLEAN()
 * @method static int USER()
 * @method static int CHANNEL()
 * @method static int ROLE()
 * @method static int MENTIONABLE()
 * @method static int NUMBER()
 * @method static int ATTACHMENT()
 */
final class SlashCommandOptionTypes {
	use EnumTrait;
	
	protected static function make(): void {
		self::register('SUB_COMMAND',	1);
		self::register('SUB_COMMAND_GROUP', 2);
		self::register('STRING', 3);
		self::register('INTEGER',	4);
		self::register('BOOLEAN',	5);
		self::register('USER', 6);
		self::register('CHANNEL',	7);
		self::register('ROLE', 8);
		self::register('MENTIONABLE',	9);
		self::register('NUMBER', 10);
		self::register('ATTACHMENT', 11);
	}
}