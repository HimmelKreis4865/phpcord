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
 * @method static int PONG()
 * @method static int CHANNEL_MESSAGE_WITH_SOURCE()
 * @method static int DEFERRED_CHANNEL_MESSAGE_WITH_SOURCE()
 * @method static int DEFERRED_UPDATE_MESSAGE()
 * @method static int UPDATE_MESSAGE()
 * @method static int APPLICATION_COMMAND_AUTOCOMPLETE_RESULT()
 * @method static int MODAL()
 */
final class InteractionResponseTypes {
	use EnumTrait;
	
	protected static function make(): void {
		self::register('PONG', 1);
		self::register('CHANNEL_MESSAGE_WITH_SOURCE', 4);
		self::register('DEFERRED_CHANNEL_MESSAGE_WITH_SOURCE', 5);
		self::register('DEFERRED_UPDATE_MESSAGE', 6);
		self::register('UPDATE_MESSAGE', 7);
		self::register('APPLICATION_COMMAND_AUTOCOMPLETE_RESULT', 8);
		self::register('MODAL', 9);
	}
}