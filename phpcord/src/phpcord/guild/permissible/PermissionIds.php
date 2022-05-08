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

namespace phpcord\guild\permissible;

use phpcord\utils\enum\EnumToolsTrait;
use phpcord\utils\enum\EnumTrait;

/**
 * @method static int CREATE_INSTANT_INVITE()
 * @method static int KICK_MEMBERS()
 * @method static int BAN_MEMBERS()
 * @method static int ADMINISTRATOR()
 * @method static int MANAGE_CHANNELS()
 * @method static int MANAGE_GUILD()
 * @method static int ADD_REACTIONS()
 * @method static int VIEW_AUDIT_LOG()
 * @method static int PRIORITY_SPEAKER()
 * @method static int STREAM()
 * @method static int VIEW_CHANNEL()
 * @method static int SEND_MESSAGES()
 * @method static int SEND_TTS_MESSAGES()
 * @method static int MANAGE_MESSAGES()
 * @method static int EMBED_LINKS()
 * @method static int ATTACH_FILES()
 * @method static int READ_MESSAGE_HISTORY()
 * @method static int MENTION_EVERYONE()
 * @method static int USE_EXTERNAL_EMOJIS()
 * @method static int VIEW_GUILD_INSIGHTS()
 * @method static int CONNECT()
 * @method static int SPEAK()
 * @method static int MUTE_MEMBERS()
 * @method static int DEAFEN_MEMBERS()
 * @method static int MOVE_MEMBERS()
 * @method static int USE_VAD()
 * @method static int CHANGE_NICKNAME()
 * @method static int MANAGE_NICKNAMES()
 * @method static int MANAGE_ROLES()
 * @method static int MANAGE_WEBHOOKS()
 * @method static int MANAGE_EMOJIS_AND_STICKERS()
 * @method static int USE_APPLICATION_COMMANDS()
 * @method static int REQUEST_TO_SPEAK()
 * @method static int MANAGE_EVENTS()
 * @method static int MANAGE_THREADS()
 * @method static int CREATE_PUBLIC_THREADS()
 * @method static int CREATE_PRIVATE_THREA()
 * @method static int USE_EXTERNAL_STICKERS()
 * @method static int SEND_MESSAGES_IN_THREADS()
 * @method static int START_EMBEDDED_ACTIVITIES()
 * @method static int MODERATE_MEMBERS()
 */
final class PermissionIds {
	use EnumToolsTrait;
	
	
	public static function make(): void {
		self::register('CREATE_INSTANT_INVITE', 0x0000000000000001);
		self::register('KICK_MEMBERS', 0x0000000000000002);
		self::register('BAN_MEMBERS', 0x0000000000000004);
		self::register('ADMINISTRATOR', 0x0000000000000008);
		self::register('MANAGE_CHANNELS', 0x0000000000000010);
		self::register('MANAGE_GUILD', 0x0000000000000020);
		self::register('ADD_REACTIONS', 0x0000000000000040);
		self::register('VIEW_AUDIT_LOG', 0x0000000000000080);
		self::register('PRIORITY_SPEAKER', 0x0000000000000100);
		self::register('STREAM', 0x0000000000000200);
		self::register('VIEW_CHANNEL', 0x0000000000000400);
		self::register('SEND_MESSAGES', 0x0000000000000800);
		self::register('SEND_TTS_MESSAGES', 0x0000000000001000);
		self::register('MANAGE_MESSAGES', 0x0000000000002000);
		self::register('EMBED_LINKS', 0x0000000000004000);
		self::register('ATTACH_FILES', 0x0000000000008000);
		self::register('READ_MESSAGE_HISTORY', 0x0000000000010000);
		self::register('MENTION_EVERYONE', 0x0000000000020000);
		self::register('USE_EXTERNAL_EMOJIS', 0x0000000000040000);
		self::register('VIEW_GUILD_INSIGHTS', 0x0000000000080000);
		self::register('CONNECT', 0x0000000000100000);
		self::register('SPEAK', 0x0000000000200000);
		self::register('MUTE_MEMBERS', 0x0000000000400000);
		self::register('DEAFEN_MEMBERS', 0x0000000000800000);
		self::register('MOVE_MEMBERS', 0x0000000001000000);
		self::register('USE_VAD', 0x0000000002000000);
		self::register('CHANGE_NICKNAME', 0x0000000004000000);
		self::register('MANAGE_NICKNAMES', 0x0000000008000000);
		self::register('MANAGE_ROLES', 0x0000000010000000);
		self::register('MANAGE_WEBHOOKS', 0x0000000020000000);
		self::register('MANAGE_EMOJIS_AND_STICKERS', 0x0000000040000000);
		self::register('USE_APPLICATION_COMMANDS', 0x0000000080000000);
		self::register('REQUEST_TO_SPEAK', 0x0000000100000000);
		self::register('MANAGE_EVENTS', 0x0000000200000000);
		self::register('MANAGE_THREADS', 0x0000000400000000);
		self::register('CREATE_PUBLIC_THREADS', 0x0000000800000000);
		self::register('CREATE_PRIVATE_THREAD', 0x000000100000000);
		self::register('USE_EXTERNAL_STICKERS', 0x0000002000000000);
		self::register('SEND_MESSAGES_IN_THREADS', 0x0000004000000000);
		self::register('START_EMBEDDED_ACTIVITIES', 0x0000008000000000);
		self::register('MODERATE_MEMBERS', 0x0000010000000000);
	}
}