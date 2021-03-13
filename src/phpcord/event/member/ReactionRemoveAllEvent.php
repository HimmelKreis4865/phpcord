<?php

namespace phpcord\event\member;

use phpcord\guild\Emoji;
use phpcord\event\Event;

class ReactionRemoveAllEvent extends Event {
	/** @var string $channel_id */
	public $channel_id;
	
	/** @var string $message_id */
	public $message_id;
	
	/** @var string $guild_id */
	public $guild_id;
	
	/**
	 * ReactionRemoveEvent constructor.
	 *
	 * @param string $message_id
	 * @param string $channel_id
	 * @param string $guild_id
	 */
	public function __construct(string $message_id, string $channel_id, string $guild_id) {
		$this->message_id = $message_id;
		$this->channel_id = $channel_id;
		$this->guild_id = $guild_id;
	}
}