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

namespace phpcord\voice;

use phpcord\async\completable\Completable;
use phpcord\Discord;
use phpcord\exception\IndexNotFoundException;
use phpcord\guild\Guild;
use phpcord\guild\GuildMember;
use phpcord\utils\Timestamp;
use phpcord\utils\Utils;
use function var_dump;

class VoiceState {
	
	/**
	 * @param int|null $guildId
	 * @param int|null $channelId
	 * @param int $userId
	 * @param GuildMember|null $member
	 * @param string $sessionId
	 * @param bool $deaf
	 * @param bool $mute
	 * @param bool $selfMute
	 * @param bool $selfDeaf
	 * @param bool $streaming
	 * @param bool $video
	 * @param bool $suppress
	 * @param Timestamp|null $speakRequestTimestamp
	 */
	public function __construct(private ?int $guildId, private ?int $channelId, private int $userId, private ?GuildMember $member, private string $sessionId, private bool $deaf, private bool $mute, private bool $selfMute, private bool $selfDeaf, private bool $streaming, private bool $video, private bool $suppress, private ?Timestamp $speakRequestTimestamp) { }
	
	/**
	 * @return int|null
	 */
	public function getGuildId(): ?int {
		return $this->guildId;
	}
	
	public function getGuild(): ?Guild {
		return Discord::getInstance()->getClient()->getGuilds()->get($this->getGuildId());
	}
	
	/**
	 * @return int|null
	 */
	public function getChannelId(): ?int {
		return $this->channelId;
	}
	
	/**
	 * @return int
	 */
	public function getUserId(): int {
		return $this->userId;
	}
	
	/**
	 * @return GuildMember|null
	 */
	public function getMember(): ?GuildMember {
		return $this->member ??= $this->getGuild()->getMembers()->get($this->getUserId());
	}
	
	/**
	 * @return string
	 */
	public function getSessionId(): string {
		return $this->sessionId;
	}
	
	/**
	 * @return Timestamp|null
	 */
	public function getSpeakRequestTimestamp(): ?Timestamp {
		return $this->speakRequestTimestamp;
	}
	
	/**
	 * @return bool
	 */
	public function isMuted(): bool {
		return $this->mute;
	}
	
	/**
	 * @return bool
	 */
	public function isDeaf(): bool {
		return $this->deaf;
	}
	
	/**
	 * @return bool
	 */
	public function isSelfDeaf(): bool {
		return $this->selfDeaf;
	}
	
	/**
	 * @return bool
	 */
	public function isSelfMute(): bool {
		return $this->selfMute;
	}
	
	/**
	 * @return bool
	 */
	public function isSuppressed(): bool {
		return $this->suppress;
	}
	
	/**
	 * @return bool
	 */
	public function isStreaming(): bool {
		return $this->streaming;
	}
	
	/**
	 * @return bool
	 */
	public function isVideo(): bool {
		return $this->video;
	}
	
	public function getChannel(): Completable {
		if ($this->guildId)
			return (Discord::getInstance()->getClient()->getGuilds()->get($this->guildId)?->getChannel($this->channelId) ?? Completable::fail(new IndexNotFoundException('Guild ' . $this->guildId . ' does not exist!')));
		return Discord::getInstance()->getClient()->getChannel($this->channelId);
	}
	
	public static function fromArray(array $array): ?VoiceState {
		if (!Utils::contains($array, 'channel_id', 'user_id', 'session_id')) return null;
		return new VoiceState(@$array['guild_id'], $array['channel_id'], $array['user_id'], (@$array['member'] ? GuildMember::fromArray(($array['member'] + ['guild_id' => $array['guild_id']])) : null), $array['session_id'], $array['deaf'] ?? false, $array['mute'] ?? false, $array['self_mute'] ?? false, $array['self_deaf'] ?? false, $array['self_stream'] ?? false, $array['self_video'] ?? false, $array['suppress'] ?? false, (@$array['request_to_speak_timestamp'] ? Timestamp::fromDate($array['request_to_speak_timestamp']) : null));
	}
}