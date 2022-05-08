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

use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;
use phpcord\channel\ChannelTypes;
use phpcord\channel\GuildChannel;
use phpcord\channel\overwrite\MemberPermissionOverwrite;
use phpcord\channel\overwrite\RolePermissionOverwrite;
use phpcord\guild\auditlog\AuditLogEntry;
use phpcord\guild\Ban;
use phpcord\guild\components\Invite;
use phpcord\guild\components\Webhook;
use phpcord\guild\components\WelcomeScreenChannel;
use phpcord\guild\GuildMember;
use phpcord\guild\permissible\Role;
use phpcord\interaction\slash\PartialSlashCommand;
use phpcord\message\Emoji;
use phpcord\message\MessageAttachment;
use phpcord\message\Message;
use phpcord\message\Reaction;
use phpcord\user\User;
use phpcord\voice\VoiceState;
use function array_flip;
use function array_map;
use function array_values;
use function array_walk;
use function json_encode;
use function var_dump;

final class Factory {
	
	/**
	 * @param int $guildId
	 * @param array $roleData
	 *
	 * @return Role[]
	 */
	public static function createRoleArray(int $guildId, array $roleData): array {
		$roles = [];
		foreach ($roleData as $role) {
			$r = Role::fromArray(($role + ['guild_id' => $guildId]));
			if ($r) $roles[$r->getId()] = $r;
		}
		return $roles;
	}
	
	/**
	 * @param int $guildId
	 * @param array $memberData
	 *
	 * @return GuildMember[]
	 */
	public static function createMemberArray(int $guildId, array $memberData): array {
		$members = [];
		foreach ($memberData as $member)
			if (($m = GuildMember::fromArray(($member + ['guild_id' => $guildId])))) $members[$m->getId()] = $m;
		return $members;
	}
	
	public static function createChannelArray(int $guildId, array $channelData): array {
		$channels = [];
		foreach ($channelData as $channel)
			if (isset($channel['type']) and ($c = ChannelTypes::createObject($channel['type'], ($channel + ['guild_id' => $guildId]))))
				$channels[$c->getId()] = $c;
		return $channels;
	}
	
	/**
	 * @param array $attachmentData
	 *
	 * @return MessageAttachment[]
	 */
	public static function createMessageAttachments(array $attachmentData): array {
		$attachments = [];
		foreach ($attachmentData as $attachment)
			if ($a = MessageAttachment::fromArray($attachment)) $attachments[$a->getId()] = $a;
		return $attachments;
	}
	
	/**
	 * @param array $messageData
	 * @param int|null $guildId
	 *
	 * @return Message[]
	 */
	public static function createMessageArray(array $messageData, ?int $guildId = null): array {
		$messages = [];
		foreach ($messageData as $message)
			if ($m = Message::fromArray(($message + ['guild_id' => $guildId]))) $messages[$m->getId()] = $m;
		return $messages;
	}
	
	public static function createOverwriteArray(GuildChannel $channel, array $overwriteData): array {
		$overwrites = [];
		foreach ($overwriteData as $overwrite) {
			$o = match ($overwrite['type'] ?? -1) {
				0 => RolePermissionOverwrite::fromArray($channel, $overwrite),
				1 => MemberPermissionOverwrite::fromArray($channel, $overwrite),
				default => throw new InvalidArgumentException('Invalid Permission overwrite payload ' . json_encode($overwriteData) . ' encountered!')
			};
			$overwrites[$o->getId()] = $o;
		}
		return $overwrites;
	}
	
	#[Pure] public static function createRtcRegionArray(array $rtcData): array {
		$regions = [];
		foreach ($rtcData as $rtc)
			if ($r = RtcRegion::fromArray($rtc)) $regions[$r->getId()] = $r;
		return $regions;
	}
	
	public static function createUserArray(array $userData): array {
		$users = [];
		foreach ($userData as $user) {
			if ($u = User::fromArray($user)) $users[$u->getId()] = $u;
		}
		return $users;
	}
	
	public static function createAuditLogEntryArray(array $entryData): array {
		$entries = [];
		foreach ($entryData as $entry) {
			if ($e = AuditLogEntry::fromArray($entry)) $entries[$e->getId()] = $e;
		}
		return $entries;
	}
	
	public static function createVoiceStateArray(array $voiceStateData): array {
		$voiceStates = [];
		foreach ($voiceStateData as $voiceState) {
			if ($v = VoiceState::fromArray($voiceState)) $voiceStates[$v->getUserId()] = $v;
		}
		return $voiceStates;
	}
	
	#[Pure] public static function createWelcomeScreenChannelArray(array $welcomeData): array {
		$channels = [];
		foreach ($welcomeData as $channel) {
			if ($c = WelcomeScreenChannel::fromArray($channel)) $channels[$c->getId()] = $c;
		}
		return $channels;
	}
	
	public static function createInviteArray(array $inviteData): array {
		$invites = [];
		foreach ($inviteData as $invite) {
			if ($i = Invite::fromArray($invite)) $invites[$i->getCode()] = $i;
		}
		return $invites;
	}

	public static function createBanArray(int $guildId, array $banData): array {
		$bans = [];
		foreach ($banData as $ban) {
			if ($b = Ban::fromArray(($ban + ['guild_id' => $guildId]))) $bans[$b->getUser()->getId()] = $b;
		}
		return $bans;
	}
	
	public static function createReactionArray(array $reactionData): array {
		return array_values(array_map(fn(array $data) => Reaction::fromArray($data), $reactionData));
	}
	
	public static function createWebhookArray(array $webhookData): array {
		$webhooks = [];
		foreach ($webhookData as $webhook) {
			if ($w = Webhook::fromArray($webhook)) $webhooks[$w->getId()] = $w;
		}
		return $webhooks;
	}
	
	#[Pure] public static function createSlashCommandArray(array $slashCommandData): array {
		$slashCommands = [];
		foreach ($slashCommandData as $slashCommand) {
			if ($s = PartialSlashCommand::fromArray($slashCommand)) $slashCommands[$s->getId()] = $s;
		}
		return $slashCommands;
	}
	
	#[Pure] public static function createEmojiArray(int $guildId, array $emojiData): array {
		$emojis = [];
		foreach ($emojiData as $emoji) {
			if ($e = Emoji::fromArray(($emoji + ['guild_id' => $guildId]))) $emojis[$e->getId()] = $e;
		}
		return $emojis;
	}
	
	public static function createRoleIdArray(array $roles): array {
		$roles = array_flip($roles);
		array_walk($roles, fn (&$value, int $id) => $value = $id);
		return $roles;
	}
}