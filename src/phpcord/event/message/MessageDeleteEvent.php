<?php

namespace phpcord\event\message;

use phpcord\event\Event;
use phpcord\guild\GuildChannel;
use phpcord\guild\GuildDeletedMessage;

class MessageDeleteEvent extends Event {
	/** @var GuildDeletedMessage $message */
	protected $message;

	protected $channel;

	/**
	 * MessageDeleteEvent constructor.
	 *
	 * @param GuildDeletedMessage $message
	 * @param GuildChannel $channel
	 */
	public function __construct(GuildDeletedMessage $message, GuildChannel $channel) {
		$this->message = $message;
		$this->channel = $channel;
	}

	/**
	 * @return GuildDeletedMessage
	 */
	public function getMessage(): GuildDeletedMessage {
		return $this->message;
	}

	/**
	 * @return GuildChannel
	 */
	public function getChannel(): GuildChannel {
		return $this->channel;
	}
}


