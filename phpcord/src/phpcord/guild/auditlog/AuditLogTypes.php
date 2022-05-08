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

namespace phpcord\guild\auditlog;

use phpcord\utils\enum\EnumTrait;

/**
 * @method static int GUILD_UPDATE()
 * @method static int CHANNEL_CREATE()
 * @method static int CHANNEL_UPDATE()
 * @method static int CHANNEL_DELETE()
 * @method static int CHANNEL_OVERWRITE_CREATE()
 * @method static int CHANNEL_OVERWRITE_UPDATE()
 * @method static int CHANNEL_OVERWRITE_DELETE()
 * @method static int MEMBER_KICK()
 * @method static int MEMBER_PRUNE()
 * @method static int MEMBER_BAN_ADD()
 * @method static int MEMBER_BAN_REMOVE()
 * @method static int MEMBER_UPDATE()
 * @method static int MEMBER_ROLE_UPDATE()
 * @method static int MEMBER_MOVE()
 * @method static int MEMBER_DISCONNECT()
 * @method static int BOT_ADD()
 * @method static int ROLE_CREATE()
 * @method static int ROLE_UPDATE()
 * @method static int ROLE_DELETE()
 * @method static int INVITE_CREATE()
 * @method static int INVITE_UPDATE()
 * @method static int INVITE_DELETE()
 * @method static int WEBHOOK_CREATE()
 * @method static int WEBHOOK_UPDATE()
 * @method static int WEBHOOK_DELETE()
 * @method static int EMOJI_CREATE()
 * @method static int EMOJI_UPDATE()
 * @method static int EMOJI_DELETE()
 * @method static int MESSAGE_DELETE()
 * @method static int MESSAGE_BULK_DELETE()
 * @method static int MESSAGE_PIN()
 * @method static int MESSAGE_UNPIN()
 * @method static int INTEGRATION_CREATE()
 * @method static int INTEGRATION_UPDATE()
 * @method static int INTEGRATION_DELETE()
 * @method static int STAGE_INSTANCE_CREATE()
 * @method static int STAGE_INSTANCE_UPDATE()
 * @method static int STAGE_INSTANCE_DELETE()
 * @method static int STICKER_CREATE()
 * @method static int STICKER_UPDATE()
 * @method static int STICKER_DELETE()
 * @method static int GUILD_SCHEDULED_EVENT_CREATE	()
 * @method static int GUILD_SCHEDULED_EVENT_UPDATE	()
 * @method static int GUILD_SCHEDULED_EVENT_DELETE	()
 * @method static int THREAD_CREATE()
 * @method static int THREAD_UPDATE()
 * @method static int THREAD_DELETE()
 */
final class AuditLogTypes {
	use EnumTrait;
	
	protected static function make(): void {
		self::register('GUILD_UPDATE', 1);
		self::register('CHANNEL_CREATE', 10);
		self::register('CHANNEL_UPDATE', 11);
		self::register('CHANNEL_DELETE', 12);
		self::register('CHANNEL_OVERWRITE_CREATE', 13);
		self::register('CHANNEL_OVERWRITE_UPDATE', 14);
		self::register('CHANNEL_OVERWRITE_DELETE', 15);
		self::register('MEMBER_KICK', 20);
		self::register('MEMBER_PRUNE', 21);
		self::register('MEMBER_BAN_ADD', 22);
		self::register('MEMBER_BAN_REMOVE', 23);
		self::register('MEMBER_UPDATE', 24);
		self::register('MEMBER_ROLE_UPDATE', 25);
		self::register('MEMBER_MOVE', 26);
		self::register('MEMBER_DISCONNECT', 27);
		self::register('BOT_ADD', 28);
		self::register('ROLE_CREATE', 30);
		self::register('ROLE_UPDATE', 31);
		self::register('ROLE_DELETE', 32);
		self::register('INVITE_CREATE', 40);
		self::register('INVITE_UPDATE', 41);
		self::register('INVITE_DELETE', 42);
		self::register('WEBHOOK_CREATE', 50);
		self::register('WEBHOOK_UPDATE', 51);
		self::register('WEBHOOK_DELETE', 52);
		self::register('EMOJI_CREATE', 60);
		self::register('EMOJI_UPDATE', 61);
		self::register('EMOJI_DELETE', 62);
		self::register('MESSAGE_DELETE', 72);
		self::register('MESSAGE_BULK_DELETE', 73);
		self::register('MESSAGE_PIN', 74);
		self::register('MESSAGE_UNPIN', 75);
		self::register('INTEGRATION_CREATE', 80);
		self::register('INTEGRATION_UPDATE', 81);
		self::register('INTEGRATION_DELETE', 82);
		self::register('STAGE_INSTANCE_CREATE', 83);
		self::register('STAGE_INSTANCE_UPDATE', 84);
		self::register('STAGE_INSTANCE_DELETE', 85);
		self::register('STICKER_CREATE', 90);
		self::register('STICKER_UPDATE', 91);
		self::register('STICKER_DELETE', 92);
		self::register('GUILD_SCHEDULED_EVENT_CREATE', 100);
		self::register('GUILD_SCHEDULED_EVENT_UPDATE', 101);
		self::register('GUILD_SCHEDULED_EVENT_DELETE', 102);
		self::register('THREAD_CREATE', 110);
		self::register('THREAD_UPDATE', 111);
		self::register('THREAD_DELETE', 112);
	}
}