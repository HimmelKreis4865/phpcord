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

namespace phpcord\channel;

use phpcord\channel\types\dm\DMChannel;
use phpcord\channel\types\dm\GroupDMChannel;
use phpcord\channel\types\guild\GuildCategoryChannel;
use phpcord\channel\types\guild\GuildNewsChannel;
use phpcord\channel\types\guild\GuildStageVoiceChannel;
use phpcord\channel\types\guild\GuildTextChannel;
use phpcord\channel\types\guild\GuildVoiceChannel;
use phpcord\channel\types\guild\thread\NewsThread;
use phpcord\channel\types\guild\thread\PrivateThread;
use phpcord\channel\types\guild\thread\PublicThread;
use phpcord\utils\enum\EnumToolsTrait;
use function json_decode;
use function json_encode;
use function var_dump;
use const JSON_PRETTY_PRINT;

/**
 * @method static int GUILD_TEXT()
 * @method static int DM()
 * @method static int GUILD_VOICE()
 * @method static int GROUP_DM()
 * @method static int GUILD_CATEGORY()
 * @method static int GUILD_NEWS()
 * @method static int GUILD_STORE()
 * @method static int GUILD_NEWS_THREAD()
 * @method static int GUILD_PUBLIC_THREAD()
 * @method static int GUILD_PRIVATE_THREAD()
 * @method static int GUILD_STAGE_VOICE()
 */
final class ChannelTypes {
	use EnumToolsTrait;
	
	protected static function make(): void {
		self::register('GUILD_TEXT', 0);
		self::register('DM', 	1);
		self::register('GUILD_VOICE', 2);
		self::register('GROUP_DM', 3);
		self::register('GUILD_CATEGORY', 4);
		self::register('GUILD_NEWS', 5);
		self::register('GUILD_STORE', 6);
		self::register('GUILD_NEWS_THREAD', 10);
		self::register('GUILD_PUBLIC_THREAD', 11);
		self::register('GUILD_PRIVATE_THREAD', 12);
		self::register('GUILD_STAGE_VOICE', 13);
	}
	
	public static function createObject(?int $type, array $data): Channel {
		$type = $type ?? $data['type'];
		return (match ($type) {
			self::GUILD_TEXT() => GuildTextChannel::class,
			self::GUILD_VOICE() => GuildVoiceChannel::class,
			self::GUILD_CATEGORY() => GuildCategoryChannel::class,
			self::GUILD_NEWS() => GuildNewsChannel::class,
			self::GUILD_STAGE_VOICE() => GuildStageVoiceChannel::class,
			self::DM() => DMChannel::class,
			self::GROUP_DM() => GroupDMChannel::class,
			self::GUILD_NEWS_THREAD() => NewsThread::class,
			self::GUILD_PUBLIC_THREAD() => PublicThread::class,
			self::GUILD_PRIVATE_THREAD() => PrivateThread::class
		})::fromArray($data);
	}
}