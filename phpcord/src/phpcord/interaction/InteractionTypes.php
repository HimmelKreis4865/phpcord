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

namespace phpcord\interaction;

use phpcord\utils\enum\EnumTrait;

/**
 * @method static int PING()
 * @method static int APPLICATION_COMMAND()
 * @method static int MESSAGE_COMPONENT()
 * @method static int APPLICATION_COMMAND_AUTOCOMPLETE()
 * @method static int MODAL_SUBMIT()
 */
final class InteractionTypes {
	use EnumTrait;
	
	protected static function make(): void {
		self::register('PING', 1);
		self::register('APPLICATION_COMMAND', 2);
		self::register('MESSAGE_COMPONENT', 3);
		self::register('APPLICATION_COMMAND_AUTOCOMPLETE', 4);
		self::register('MODAL_SUBMIT', 5);
	}
}