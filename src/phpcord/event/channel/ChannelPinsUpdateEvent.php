<?php

namespace phpcord\event\channel;

use phpcord\event\Event;

class ChannelPinsUpdateEvent extends Event {
	/** @var string $last_pin_timestamp */
	protected $last_pin_timestamp;
	/** @var string $channel_id */
	protected $channel_id;
	/** @var string $guild_id */
	protected $guild_id;

	/**
	 * ChannelPinsUpdateEvent constructor.
	 *
	 * @param string $last_pin_timestamp
	 * @param string $channel_id
	 * @param string $guild_id
	 */
	public function __construct(string $last_pin_timestamp, string $channel_id, string $guild_id) {
		$this->last_pin_timestamp = $last_pin_timestamp;
		$this->channel_id = $channel_id;
		$this->guild_id = $guild_id;
	}

	/**
	 * @return string
	 */
	public function getChannelId(): string {
		return $this->channel_id;
	}

	/**
	 * @return string
	 */
	public function getGuildId(): string {
		return $this->guild_id;
	}

	/**
	 * @return string
	 */
	public function getLastPinTimestamp(): string {
		return $this->last_pin_timestamp;
	}
}


