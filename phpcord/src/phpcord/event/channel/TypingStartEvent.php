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

namespace phpcord\event\channel;

use phpcord\async\completable\Completable;
use phpcord\channel\GuildChannel;
use phpcord\channel\TextChannel;
use phpcord\channel\types\guild\GuildTextChannel;
use phpcord\Discord;
use phpcord\event\Event;
use phpcord\guild\Guild;
use phpcord\guild\GuildMember;
use phpcord\utils\Timestamp;

class TypingStartEvent extends Event {
	
	/**
	 * @param int|null $guildId
	 * @param int $channelId
	 * @param int $userId
	 * @param Timestamp $timestamp
	 * @param GuildMember|null $member
	 */
	public function __construct(private ?int $guildId, private int $channelId, private int $userId, private Timestamp $timestamp, private ?GuildMember $member) { }
	
	/**
	 * @return int|null
	 */
	public function getGuildId(): ?int {
		return $this->guildId;
	}
	
	public function getGuild(): ?Guild {
		return ($this->guildId ? Discord::getInstance()->getClient()->getGuilds()->get($this->getGuild()) : null);
	}
	
	/**
	 * @return int
	 */
	public function getChannelId(): int {
		return $this->channelId;
	}
	
	/**
	 * @return Completable<TextChannel>
	 */
	public function getChannel(): Completable {
		return ($this->getGuildId() ? $this->getGuild()->getChannel($this->channelId) : Discord::getInstance()->getClient()->getChannel($this->channelId));
	}
	
	/**
	 * @return int
	 */
	public function getUserId(): int {
		return $this->userId;
	}
	
	/**
	 * @return Timestamp
	 */
	public function getTimestamp(): Timestamp {
		return $this->timestamp;
	}
	
	/**
	 * @return GuildMember|null
	 */
	public function getMember(): ?GuildMember {
		return $this->member;
	}
}