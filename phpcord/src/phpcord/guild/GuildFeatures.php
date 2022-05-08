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

namespace phpcord\guild;

use phpcord\utils\enum\EnumTrait;

/**
 * @method static string ANIMATED_ICON()
 * @method static string BANNER()
 * @method static string COMMERCE()
 * @method static string COMMUNITY()
 * @method static string DISCOVERABLE()
 * @method static string FEATURABLE()
 * @method static string INVITE_SPLASH()
 * @method static string MEMBER_VERIFICATION_GATE_ENABLED()
 * @method static string MONETIZATION_ENABLED()
 * @method static string MORE_STICKERS()
 * @method static string NEWS()
 * @method static string PARTNERED()
 * @method static string PREVIEW_ENABLE()
 * @method static string PRIVATE_THREADS()
 * @method static string ROLE_ICONS()
 * @method static string SEVEN_DAY_THREAD_ARCHIVE()
 * @method static string VANITY_URL()
 * @method static string VERIFIED()
 * @method static string VIP_REGIONS()
 * @method static string WELCOME_SCREEN_ENABLED()
 */
final class GuildFeatures {
	use EnumTrait;
	
	protected static function make(): void {
		self::register('ANIMATED_ICON', 'ANIMATED_ICON');
		self::register('BANNER', 'BANNER');
		self::register('COMMERCE', 'COMMERCE');
		self::register('COMMUNITY', 'COMMUNITY');
		self::register('DISCOVERABLE', 'DISCOVERABLE');
		self::register('FEATURABLE', 'FEATURABLE');
		self::register('INVITE_SPLASH', 'INVITE_SPLASH');
		self::register('MEMBER_VERIFICATION_GATE_ENABLED', 'MEMBER_VERIFICATION_GATE_ENABLED');
		self::register('MONETIZATION_ENABLED', 'MONETIZATION_ENABLED');
		self::register('MORE_STICKERS', 'MORE_STICKERS');
		self::register('NEWS', 'NEWS');
		self::register('PARTNERED', 'PARTNERED');
		self::register('PREVIEW_ENABLE', 'PREVIEW_ENABLE');
		self::register('PRIVATE_THREADS', 'PRIVATE_THREADS');
		self::register('ROLE_ICONS', 'ROLE_ICONS');
		self::register('SEVEN_DAY_THREAD_ARCHIVE', 'SEVEN_DAY_THREAD_ARCHIVE');
		self::register('VANITY_URL', 'VANITY_URL');
		self::register('VERIFIED', 'VERIFIED');
		self::register('VIP_REGIONS', 'VIP_REGIONS');
		self::register('WELCOME_SCREEN_ENABLED', 'WELCOME_SCREEN_ENABLED');
	}
}