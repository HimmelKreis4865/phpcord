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

namespace phpcord\guild\data;

use phpcord\utils\enum\EnumTrait;

/**
 * @method static int SUPPRESS_JOIN_NOTIFICATIONS()  Suppress member join notifications
 * @method static int SUPPRESS_PREMIUM_SUBSCRIPTIONS()	 Suppress server boost notifications
 * @method static int SUPPRESS_GUILD_REMINDER_NOTIFICATIONS()  Suppress server setup tips
 * @method static int SUPPRESS_JOIN_NOTIFICATION_REPLIES()  Hide member join sticker reply buttons
 */
final class SystemChannelFlags {
	use EnumTrait;
	
	protected static function make(): void {
		self::register('SUPPRESS_JOIN_NOTIFICATIONS', 1 << 0);
		self::register('SUPPRESS_PREMIUM_SUBSCRIPTIONS', 1 << 1);
		self::register('SUPPRESS_GUILD_REMINDER_NOTIFICATIONS', 1 << 2);
		self::register('SUPPRESS_JOIN_NOTIFICATION_REPLIES', 1 << 3);
	}
}