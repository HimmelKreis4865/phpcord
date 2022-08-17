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

namespace phpcord\intent;

use JetBrains\PhpStorm\Pure;
use phpcord\utils\enum\EnumTrait;

/**
 * @method static string READY()
 * @method static string GUILD_CREATE()
 * @method static string GUILD_UPDATE()
 * @method static string GUILD_DELETE()
 * @method static string GUILD_ROLE_CREATE()
 * @method static string GUILD_ROLE_UPDATE()
 * @method static string GUILD_ROLE_DELETE()
 * @method static string CHANNEL_CREATE()
 * @method static string CHANNEL_UPDATE()
 * @method static string CHANNEL_DELETE()
 * @method static string CHANNEL_PINS_UPDATE()
 * @method static string THREAD_CREATE()
 * @method static string THREAD_UPDATE()
 * @method static string THREAD_DELETE()
 * @method static string THREAD_LIST_SYNC()
 * @method static string THREAD_MEMBER_UPDATE()
 * @method static string THREAD_MEMBERS_UPDATE()
 * @method static string STAGE_INSTANCE_CREATE()
 * @method static string STAGE_INSTANCE_UPDATE()
 * @method static string STAGE_INSTANCE_DELETE()
 * @method static string GUILD_MEMBER_ADD()
 * @method static string GUILD_MEMBER_UPDATE()
 * @method static string GUILD_MEMBER_REMOVE()
 * @method static string GUILD_BAN_ADD()
 * @method static string GUILD_BAN_REMOVE()
 * @method static string GUILD_EMOJIS_UPDATE()
 * @method static string GUILD_STICKERS_UPDATE()
 * @method static string GUILD_INTEGRATIONS_UPDATE()
 * @method static string INTEGRATION_CREATE()
 * @method static string INTEGRATION_UPDATE()
 * @method static string INTEGRATION_DELETE()
 * @method static string WEBHOOKS_UPDATE()
 * @method static string INVITE_CREATE()
 * @method static string INVITE_DELETE()
 * @method static string VOICE_STATE_UPDATE()
 * @method static string PRESENCE_UPDATE()
 * @method static string MESSAGE_CREATE()
 * @method static string MESSAGE_UPDATE()
 * @method static string MESSAGE_DELETE()
 * @method static string MESSAGE_DELETE_BULK()
 * @method static string MESSAGE_REACTION_ADD()
 * @method static string MESSAGE_REACTION_REMOVE()
 * @method static string MESSAGE_REACTION_REMOVE_ALL()
 * @method static string MESSAGE_REACTION_REMOVE_EMOJI()
 * @method static string TYPING_START()
 * @method static string GUILD_SCHEDULED_EVENT_CREATE()
 * @method static string GUILD_SCHEDULED_EVENT_UPDATE()
 * @method static string GUILD_SCHEDULED_EVENT_DELETE()
 * @method static string GUILD_SCHEDULED_EVENT_USER_ADD()
 * @method static string GUILD_SCHEDULED_EVENT_USER_REMOVE()
 * @method static string VOICE_SERVER_UPDATE()
 * @method static string INTERACTION_CREATE()
 */
final class Intents {
	use EnumTrait;

	public const SUM_GUILDS =  (1 << 0);
	
	public const SUM_GUILD_MEMBERS =  (1 << 1);
	
	public const SUM_GUILD_BANS =  (1 << 2);
	
	public const SUM_GUILD_EMOJIS_AND_STICKERS =  (1 << 3);
	
	public const SUM_GUILD_INTEGRATIONS =  (1 << 4);
	
	public const SUM_GUILD_WEBHOOKS =  (1 << 5);
	
	public const SUM_GUILD_INVITES =  (1 << 6);
	
	public const SUM_GUILD_VOICE_STATES =  (1 << 7);
	
	public const SUM_GUILD_PRESENCES =  (1 << 8);
	
	public const SUM_GUILD_MESSAGES =  (1 << 9);
	
	public const SUM_GUILD_MESSAGE_REACTIONS =  (1 << 10);
	
	public const SUM_GUILD_MESSAGE_TYPING =  (1 << 11);
	
	public const SUM_DIRECT_MESSAGES =  (1 << 12);
	
	public const SUM_DIRECT_MESSAGE_REACTIONS =  (1 << 13);
	
	public const SUM_DIRECT_MESSAGE_TYPING =  (1 << 14);
	
	public const SUM_GUILD_SCHEDULED_EVENTS =  (1 << 16);
	
	
	/**
	 * This does not include guild presences, as there's only a few applications for them, and it could spam packets on bigger servers
	 *
	 * @return int
	 */
	public static function recommendedIntents(): int {
		return self::SUM_GUILDS | self::SUM_GUILD_MEMBERS | self::SUM_GUILD_BANS | self::SUM_GUILD_EMOJIS_AND_STICKERS |
			   self::SUM_GUILD_INTEGRATIONS | self::SUM_GUILD_WEBHOOKS | self::SUM_GUILD_INVITES | self::SUM_GUILD_VOICE_STATES |
			   self::SUM_DIRECT_MESSAGES | self::SUM_DIRECT_MESSAGE_REACTIONS | self::SUM_GUILD_MESSAGES | self::SUM_GUILD_MESSAGE_REACTIONS | self::SUM_GUILD_MESSAGE_TYPING | self::SUM_DIRECT_MESSAGE_TYPING | self::SUM_GUILD_SCHEDULED_EVENTS;
	}
	
	#[Pure] public static function all(): int {
		return self::recommendedIntents() | self::SUM_GUILD_PRESENCES;
	}
	
	protected static function make(): void {
		self::register('READY', 'READY');
		self::register('GUILD_CREATE', 'GUILD_CREATE');
		self::register('GUILD_UPDATE', 'GUILD_UPDATE');
		self::register('GUILD_DELETE', 'GUILD_DELETE');
		self::register('GUILD_ROLE_CREATE', 'GUILD_ROLE_CREATE');
		self::register('GUILD_ROLE_UPDATE', 'GUILD_ROLE_UPDATE');
		self::register('GUILD_ROLE_DELETE', 'GUILD_ROLE_DELETE');
		self::register('CHANNEL_CREATE', 'CHANNEL_CREATE');
		self::register('CHANNEL_UPDATE', 'CHANNEL_UPDATE');
		self::register('CHANNEL_DELETE', 'CHANNEL_DELETE');
		self::register('CHANNEL_PINS_UPDATE', 'CHANNEL_PINS_UPDATE');
		self::register('THREAD_CREATE', 'THREAD_CREATE');
		self::register('THREAD_UPDATE', 'THREAD_UPDATE');
		self::register('THREAD_DELETE', 'THREAD_DELETE');
		self::register('THREAD_LIST_SYNC', 'THREAD_LIST_SYNC');
		self::register('THREAD_MEMBER_UPDATE', 'THREAD_MEMBER_UPDATE');
		self::register('THREAD_MEMBERS_UPDATE', 'THREAD_MEMBERS_UPDATE');
		self::register('STAGE_INSTANCE_CREATE', 'STAGE_INSTANCE_CREATE');
		self::register('STAGE_INSTANCE_UPDATE', 'STAGE_INSTANCE_UPDATE');
		self::register('STAGE_INSTANCE_DELETE', 'STAGE_INSTANCE_DELETE');
		self::register('GUILD_MEMBER_ADD', 'GUILD_MEMBER_ADD');
		self::register('GUILD_MEMBER_UPDATE', 'GUILD_MEMBER_UPDATE');
		self::register('GUILD_MEMBER_REMOVE', 'GUILD_MEMBER_REMOVE');
		self::register('THREAD_MEMBERS_UPDATE', 'THREAD_MEMBERS_UPDATE');
		self::register('GUILD_BAN_ADD', 'GUILD_BAN_ADD');
		self::register('GUILD_BAN_REMOVE', 'GUILD_BAN_REMOVE');
		self::register('GUILD_EMOJIS_UPDATE', 'GUILD_EMOJIS_UPDATE');
		self::register('GUILD_STICKERS_UPDATE', 'GUILD_STICKERS_UPDATE');
		self::register('GUILD_INTEGRATIONS_UPDATE', 'GUILD_INTEGRATIONS_UPDATE');
		self::register('INTEGRATION_CREATE', 'INTEGRATION_CREATE');
		self::register('INTEGRATION_UPDATE', 'INTEGRATION_UPDATE');
		self::register('INTEGRATION_DELETE', 'INTEGRATION_DELETE');
		self::register('WEBHOOKS_UPDATE', 'WEBHOOKS_UPDATE');
		self::register('INVITE_CREATE', 'INVITE_CREATE');
		self::register('INVITE_DELETE', 'INVITE_DELETE');
		self::register('VOICE_STATE_UPDATE', 'VOICE_STATE_UPDATE');
		self::register('PRESENCE_UPDATE', 'PRESENCE_UPDATE');
		self::register('MESSAGE_CREATE', 'MESSAGE_CREATE');
		self::register('MESSAGE_UPDATE', 'MESSAGE_UPDATE');
		self::register('MESSAGE_DELETE', 'MESSAGE_DELETE');
		self::register('MESSAGE_DELETE_BULK', 'MESSAGE_DELETE_BULK');
		self::register('MESSAGE_REACTION_ADD', 'MESSAGE_REACTION_ADD');
		self::register('MESSAGE_REACTION_REMOVE', 'MESSAGE_REACTION_REMOVE');
		self::register('MESSAGE_REACTION_REMOVE_ALL', 'MESSAGE_REACTION_REMOVE_ALL');
		self::register('MESSAGE_REACTION_REMOVE_EMOJI', 'MESSAGE_REACTION_REMOVE_EMOJI');
		self::register('TYPING_START', 'TYPING_START');
		self::register('MESSAGE_CREATE', 'MESSAGE_CREATE');
		self::register('MESSAGE_UPDATE', 'MESSAGE_UPDATE');
		self::register('MESSAGE_DELETE', 'MESSAGE_DELETE');
		self::register('CHANNEL_PINS_UPDATE', 'CHANNEL_PINS_UPDATE');
		self::register('MESSAGE_REACTION_ADD', 'MESSAGE_REACTION_ADD');
		self::register('MESSAGE_REACTION_REMOVE', 'MESSAGE_REACTION_REMOVE');
		self::register('MESSAGE_REACTION_REMOVE_ALL', 'MESSAGE_REACTION_REMOVE_ALL');
		self::register('MESSAGE_REACTION_REMOVE_EMOJI', 'MESSAGE_REACTION_REMOVE_EMOJI');
		self::register('TYPING_START', 'TYPING_START');
		self::register('GUILD_SCHEDULED_EVENT_CREATE', 'GUILD_SCHEDULED_EVENT_CREATE');
		self::register('GUILD_SCHEDULED_EVENT_UPDATE', 'GUILD_SCHEDULED_EVENT_UPDATE');
		self::register('GUILD_SCHEDULED_EVENT_DELETE', 'GUILD_SCHEDULED_EVENT_DELETE');
		self::register('GUILD_SCHEDULED_EVENT_USER_ADD', 'GUILD_SCHEDULED_EVENT_USER_ADD');
		self::register('GUILD_SCHEDULED_EVENT_USER_REMOVE', 'GUILD_SCHEDULED_EVENT_USER_REMOVE');
		self::register('VOICE_SERVER_UPDATE', 'VOICE_SERVER_UPDATE');
		self::register('INTERACTION_CREATE', 'INTERACTION_CREATE');
	}
}