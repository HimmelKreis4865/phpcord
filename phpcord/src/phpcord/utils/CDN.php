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

use JetBrains\PhpStorm\Pure;
use phpcord\utils\enum\EnumParameterHandle;
use phpcord\utils\enum\EnumTrait;
use function sprintf;

/**
 * @method static string BASE()
 * @method static string CUSTOM_EMOJI(int $id)
 * @method static string GUILD_ICON(int $id, string $iconHash)
 * @method static string GUILD_SPLASH(int $id, string $hash)
 * @method static string GUILD_DISCOVERY_SPLASH(int $id, string $hash)
 * @method static string GUILD_BANNER(int $id, string $hash)
 * @method static string USER_BANNER(int $id, string $hash)
 * @method static string DEFAULT_USER_AVATAR()
 * @method static string USER_AVATAR(int $id, string $avatarHash)
 * @method static string GUILD_MEMBER_AVATAR()
 * @method static string APPLICATION_ICON(int $applicationId, string $iconHash)
 * @method static string APPLICATION_COVER()
 * @method static string APPLICATION_ASSET()
 * @method static string ACHIEVEMENT_ICON()
 * @method static string STICKER_PACK_BANNER()
 * @method static string TEAM_ICON()
 * @method static string STICKER()
 * @method static string ROLE_ICON(int $id, string $iconHash)
 */
final class CDN {
	use EnumTrait;
	
	protected static function make(): void {
		self::register('base','https://cdn.discordapp.com/');
		self::register('Custom Emoji', self::createParameter('emojis/%s.png'));
		self::register('Guild Icon', self::createParameter('icons/%s/%s.png'));
		self::register('Guild Splash', self::createParameter('splashes/%s/%s.png'));
		self::register('Guild Discovery Splash', self::createParameter('discovery-splashes/%s/%s.png'));
		self::register('Guild Banner', self::createParameter('banners/%s/%s.png'));
		self::register('User Banner', self::createParameter('banners/%s/%s.png'));
		self::register('Default User Avatar', self::createParameter('embed/avatars/%s.png'));
		self::register('User Avatar', self::createParameter('avatars/%s/%s.png'));
		self::register('Guild Member Avatar', self::createParameter('guilds/%s/users/%s/avatars/%s.png'));
		self::register('Application Icon', self::createParameter('app-icons/%s/%s.png'));
		self::register('Application Cover', self::createParameter('app-icons/%s/%s.png'));
		self::register('Application Asset', self::createParameter('app-assets/%s/%s.png'));
		self::register('Achievement Icon', self::createParameter('app-assets/%s/achievements/%s/icons/%s.png'));
		self::register('Sticker Pack Banner', self::createParameter('app-assets/710982414301790216/store/%s.png'));
		self::register('Team Icon', self::createParameter('team-icons/%s/%s.png'));
		self::register('Sticker', self::createParameter('stickers/%s.png'));
		self::register('Role Icon', self::createParameter('role-icons/%s/%s.png'));
	}
	
	/**
	 * @param string $path
	 *
	 * @return EnumParameterHandle
	 */
	#[Pure] private static function createParameter(string $path): EnumParameterHandle {
		return new EnumParameterHandle(fn(mixed ...$parameter) => self::base() . sprintf($path, ...$parameter));
	}
}