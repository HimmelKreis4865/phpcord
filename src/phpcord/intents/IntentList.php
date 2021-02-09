<?php

namespace phpcord\intents;

interface IntentList {
	/**
	 * Guilds intent:.
	 *
	 * - GUILD_CREATE
	 * - GUILD_UPDATE
	 * - GUILD_DELETE
	 * - GUILD_ROLE_CREATE
	 * - GUILD_ROLE_UPDATE
	 * - GUILD_ROLE_DELETE
	 * - CHANNEL_CREATE
	 * - CHANNEL_UPDATE
	 * - CHANNEL_DELETE
	 * - CHANNEL_PINS_UPDATE
	 */
	const GUILDS = (1 << 0);

	/**
	 * Guild member events:.
	 *
	 * - GUILD_MEMBER_ADD
	 * - GUILD_MEMBER_UPDATE
	 * - GUILD_MEMBER_REMOVE
	 */
	const GUILD_MEMBERS = (1 << 1);

	/**
	 * Guild ban events:.
	 *
	 * - GUILD_BAN_ADD
	 * - GUILD_BAN_REMOVE
	 */
	const GUILD_BANS = (1 << 2);

	/**
	 * Guild emoji events:.
	 *
	 * - GUILD_EMOJIS_UPDATE
	 */
	const GUILD_EMOJIS = (1 << 3);

	/**
	 * Guild integration events:.
	 *
	 * - GUILD_INTEGRATIONS_UPDATE
	 */
	const GUILD_INTEGRATIONS = (1 << 4);

	/**
	 * Guild webhook events.
	 *
	 * - WEBHOOKS_UPDATE
	 */
	const GUILD_WEBHOOKS = (1 << 5);

	/**
	 * Guild invite events:.
	 *
	 * - INVITE_CREATE
	 * - INVITE_DELETE
	 */
	const GUILD_INVITES = (1 << 6);

	/**
	 * Guild voice state events:.
	 *
	 * - VOICE_STATE_UPDATE
	 */
	const GUILD_VOICE_STATES = (1 << 7);

	/**
	 * Guild presence events:.
	 *
	 * - PRESENECE_UPDATE
	 */
	const GUILD_PRESENCES = (1 << 8);

	/**
	 * Guild message events:.
	 *
	 * - MESSAGE_CREATE
	 * - MESSAGE_UPDATE
	 * - MESSAGE_DELETE
	 * - MESSAGE_DELETE_BULK
	 */
	const GUILD_MESSAGES = (1 << 9);

	/**
	 * Guild message reaction events:.
	 *
	 * - MESSAGE_REACTION_ADD
	 * - MESSAGE_REACTION_REMOVE
	 * - MESSAGE_REACTION_REMOVE_ALL
	 * - MESSAGE_REACTION_REMOVE_EMOJI
	 */
	const GUILD_MESSAGE_REACTIONS = (1 << 10);

	/**
	 * Guild typing events:.
	 *
	 * - TYPING_START
	 */
	const GUILD_MESSAGE_TYPING = (1 << 11);

	/**
	 * Direct message events:.
	 *
	 * - CHANNEL_CREATE
	 * - MESSAGE_CREATE
	 * - MESSAGE_UPDATE
	 * - MESSAGE_DELETE
	 * - CHANNEL_PINS_UPDATE
	 */
	const DIRECT_MESSAGES = (1 << 12);

	/**
	 * Direct message reaction events:.
	 *
	 * - MESSAGE_REACTION_ADD
	 * - MESSAGE_REACTION_REMOVE
	 * - MESSAGE_REACTION_REMOVE_ALL
	 * - MESSAGE_REACTION_REMOVE_EMOJI
	 */
	const DIRECT_MESSAGE_REACTIONS = (1 << 13);

	/**
	 * Direct message typing events:.
	 *
	 * - TYPING_START
	 */
	const DIRECT_MESSAGE_TYPING = (1 << 14);

	const INTENTS = [
		"GUILD_CREATE",
		"GUILD_UPDATE",
		"GUILD_DELETE",
		"GUILD_ROLE_CREATE",
		"GUILD_ROLE_UPDATE",
		"GUILD_ROLE_DELETE",
		"CHANNEL_CREATE",
		"CHANNEL_UPDATE",
		"CHANNEL_DELETE",
		"CHANNEL_PINS_UPDATE",
		"GUILD_MEMBER_ADD",
		"GUILD_MEMBER_UPDATE",
		"GUILD_MEMBER_REMOVE",
		"GUILD_BAN_ADD",
	  	"GUILD_BAN_REMOVE",
		"GUILD_EMOJIS_UPDATE",
		"GUILD_INTEGRATIONS_UPDATE",
		"WEBHOOKS_UPDATE",
		"INVITE_CREATE",
		"INVITE_DELETE",
		"VOICE_STATE_UPDATE",
		"PRESENCE_UPDATE",
		"MESSAGE_CREATE",
		"MESSAGE_UPDATE",
		"MESSAGE_DELETE",
		"MESSAGE_DELETE_BULK",
		"MESSAGE_REACTION_ADD",
		"MESSAGE_REACTION_REMOVE",
		"MESSAGE_REACTION_REMOVE_ALL",
		"MESSAGE_REACTION_REMOVE_EMOJI",
		"TYPING_START",
		"MESSAGE_CREATE",
		"MESSAGE_UPDATE",
		"MESSAGE_DELETE",
		"CHANNEL_PINS_UPDATE",
		"DIRECT_MESSAGE_TYPING"
	];
}


