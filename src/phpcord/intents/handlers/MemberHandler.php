<?php

namespace phpcord\intents\handlers;

use phpcord\channel\VoiceChannel;
use phpcord\Discord;
use phpcord\event\member\MemberAddEvent;
use phpcord\event\member\MemberTypingStartEvent;
use phpcord\event\member\MemberUpdateEvent;
use phpcord\event\user\MemberRemoveEvent;
use phpcord\event\user\PresenceUpdateEvent;
use phpcord\event\voice\VoiceJoinEvent;
use phpcord\event\voice\VoiceLeaveEvent;
use phpcord\event\voice\VoiceStreamingEndEvent;
use phpcord\guild\Guild;
use phpcord\guild\VoiceStateData;
use phpcord\utils\GuildSettingsInitializer;
use phpcord\utils\MemberInitializer;
use phpcord\event\voice\VoiceDeafenEvent;
use phpcord\event\voice\VoiceMuteEvent;
use phpcord\event\voice\VoiceServerDeafenEvent;
use phpcord\event\voice\VoiceServerMuteEvent;
use phpcord\event\voice\VoiceServerUnDeafenEvent;
use phpcord\event\voice\VoiceServerUnMuteEvent;
use phpcord\event\voice\VoiceStreamingStartEvent;
use phpcord\event\voice\VoiceUnDeafenEvent;
use phpcord\event\voice\VoiceUnMuteEvent;
use phpcord\event\voice\VoiceVideoEndEvent;
use phpcord\event\voice\VoiceVideoStartEvent;

class MemberHandler extends BaseIntentHandler {

	public function getIntents(): array {
		return ["GUILD_MEMBER_ADD", "GUILD_MEMBER_UPDATE", "GUILD_MEMBER_REMOVE", "TYPING_START", "PRESENCE_UPDATE", "VOICE_STATE_UPDATE"];
	}

	public function handle(Discord $discord, string $intent, array $data) {
		switch ($intent) {
			case "GUILD_MEMBER_ADD":
				$member = MemberInitializer::createMember($data, $data["guild_id"]);
				(new MemberAddEvent($member))->call();
				$guild = $discord->client->getGuild($member->getGuildId());
				if (!$guild instanceof Guild) return;
				$guild->addMember($member);
				$guild->member_count++;
				break;

			case "GUILD_MEMBER_UPDATE":
				$member = MemberInitializer::createMember($data, $data["guild_id"]);
				(new MemberUpdateEvent($member))->call();
				$guild = $discord->client->getGuild($member->getGuildId());
				if (!$guild instanceof Guild) return;
				$guild->updateMember($member);
				break;

			case "GUILD_MEMBER_REMOVE":
				$member = MemberInitializer::createUser($data["user"], $data["guild_id"]);
				(new MemberRemoveEvent($member))->call();
				$guild = $discord->client->getGuild($member->getGuildId());
				if (!$guild instanceof Guild) return;
				$guild->removeMember($member);
				
				$guild->member_count--;
				break;

			case "TYPING_START":
				(new MemberTypingStartEvent($data["user_id"], $data["timestamp"], $data["channel_id"]))->call();
				break;
			
			case "PRESENCE_UPDATE":
				(new PresenceUpdateEvent(@$data["user"]["id"], $data["status"] ?? "", $data["activities"] ?? []))->call();
				break;
				
			case "VOICE_STATE_UPDATE":
				
				$state = GuildSettingsInitializer::createVoiceState($data);
				$member = MemberInitializer::createMember($data["member"] ?? [], $data["guild_id"] ?? "-");
				
				// voice leave
				if (($channel = @$data["channel_id"]) === null) {
					if ($member !== null) $channel = $member->getGuild()->removeMemberFromVoice($member->getId());
					(new VoiceLeaveEvent($channel, $state, $member))->call();
					return;
				}
				
				/** @var VoiceChannel $vc */
				if (($vc = Discord::getInstance()->getClient()->getGuild($data["guild_id"] ?? "-")->getChannel($channel)) instanceof VoiceChannel) {
					// voice enter
					if (!isset($vc->users[$data["user_id"]])) {
						$vc->users[$data["user_id"]] = $state;
						(new VoiceJoinEvent($channel, $state, $member))->call();
						return;
					}
					$oldState = clone $vc->users[$data["user_id"]];
					if (!$oldState instanceof VoiceStateData) return;
					$vc->users[$data["user_id"]] = $state;
					
					// todo: how to improve that ugly peace of code?
				
					if ($state->isDeafened() and !$oldState->isDeafened()) {
						(new VoiceDeafenEvent($channel, $state, $member))->call();
					} else if (!$state->isDeafened() and $oldState->isDeafened()) {
						(new VoiceUnDeafenEvent($channel, $state, $member))->call();
					} else if ($state->isMuted() and !$oldState->isMuted()) {
						(new VoiceMuteEvent($channel, $state, $member))->call();
					} else if (!$state->isMuted() and $oldState->isMuted()) {
						(new VoiceUnMuteEvent($channel, $state, $member))->call();
					} else if ($state->isGlobalDeafened() and !$oldState->isGlobalDeafened()) {
						(new VoiceServerDeafenEvent($channel, $state, $member))->call();
					} else if ($state->isGlobalDeafened() and !$oldState->isGlobalDeafened()) {
						(new VoiceServerUnDeafenEvent($channel, $state, $member))->call();
					} else if ($state->isGlobalMuted() and !$oldState->isGlobalMuted()) {
						(new VoiceServerMuteEvent($channel, $state, $member))->call();
					} else if (!$state->isGlobalMuted() and $oldState->isGlobalMuted()) {
						(new VoiceServerUnMuteEvent($channel, $state, $member))->call();
					} else if ($state->isStreaming() and !$oldState->isStreaming()) {
						(new VoiceStreamingStartEvent($channel, $state, $member))->call();
					} else if (!$state->isStreaming() and $oldState->isStreaming()) {
						(new VoiceStreamingEndEvent($channel, $state, $member))->call();
					} else if ($state->hasVideo() and !$oldState->hasVideo()) {
						(new VoiceVideoStartEvent($channel, $state, $member))->call();
					} else if (!$state->hasVideo() and $oldState->hasVideo()) {
						(new VoiceVideoEndEvent($channel, $state, $member))->call();
					}
				}
				break;
		}
	}
}