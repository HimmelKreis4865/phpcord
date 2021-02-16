<?php

namespace phpcord\event\member;

use phpcord\guild\Emoji;
use phpcord\event\Event;

class ReactionRemoveEvent extends Event {
	/** @var string $channel_id */
	public $channel_id;
	
	/** @var string $message_id */
	public $message_id;
	
	/** @var string $user_id */
	public $user_id;
	
	/** @var Emoji|null $emoji */
	public $emoji;
	
	/** @var bool $all */
	protected $all;

	/**
	 * ReactionRemoveEvent constructor.
	 *
	 * @param string $user_id
	 * @param string $message_id
	 * @param string $channel_id
	 * @param Emoji|null $emoji
	 * @param bool $all
	 */
	public function __construct(string $user_id, string $message_id, string $channel_id, ?Emoji $emoji, bool $all = false) {
		$this->user_id = $user_id;
		$this->message_id = $message_id;
		$this->channel_id = $channel_id;
		$this->emoji = $emoji;
		$this->all = $all;
	}

	/**
	 * Returns whether the action removed all reactions or not
	 *
	 * @api
	 *
	 * @return bool
	 */
	public function removedAll(): bool {
		return $this->all;
	}
}