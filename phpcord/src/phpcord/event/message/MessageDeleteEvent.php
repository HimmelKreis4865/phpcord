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

namespace phpcord\event\message;

use phpcord\async\completable\Completable;
use phpcord\channel\TextChannel;
use phpcord\Discord;
use phpcord\event\Event;
use phpcord\guild\Guild;

class MessageDeleteEvent extends Event {
	
	/**
	 * @param int|null $guildId
	 * @param int $channelId
	 * @param int $id
	 */
	public function __construct(private ?int $guildId, private int $channelId, private int $id) { }
	
	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
	}
	
	public function getGuild(): ?Guild {
		if (!$this->guildId) return null;
		return Discord::getInstance()->getClient()->getGuilds()->get($this->guildId);
	}
	
	/**
	 * @return Completable<TextChannel>
	 */
	public function getChannel(): Completable {
		return ($this->guildId ? $this->getGuild()->getChannel($this->channelId) : Discord::getInstance()->getClient()->getChannel($this->channelId));
	}
}