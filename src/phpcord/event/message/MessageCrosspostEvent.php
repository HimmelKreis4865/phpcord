<?php

namespace phpcord\event\message;

use phpcord\event\Event;

class MessageCrosspostEvent extends Event {
	
	protected $id;
	
	protected $channelId;
	
	protected $guildId;
	
	public function __construct(string $id, string $channelId, string $guildId) {
		$this->id = $id;
		$this->channelId = $channelId;
		$this->guildId = $guildId;
	}
	
	/**
	 * Returns the id of the message crossposted
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getMessageId(): string {
		return $this->id;
	}
	
	/**
	 * Returns the id of the channel the message was sent (and crossposted in)
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getChannelId(): string {
		return $this->channelId;
	}
	
	/**
	 * Returns the id of the guild the message was crossposted in
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getGuildId(): string {
		return $this->guildId;
	}
}