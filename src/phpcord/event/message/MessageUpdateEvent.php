<?php

namespace phpcord\event\message;

use phpcord\event\Event;
use phpcord\guild\GuildChannel;
use phpcord\guild\GuildUpdatedMessage;

class MessageUpdateEvent extends Event {
	/** @var GuildUpdatedMessage $message */
	protected $message;
	/** @var GuildChannel $channel */
	protected $channel;

	/**
	 * MessageUpdateEvent constructor.
	 *
	 * @param GuildUpdatedMessage $message
	 * @param GuildChannel $channel
	 */
	public function __construct(GuildUpdatedMessage $message, GuildChannel $channel) {
		$this->message = $message;
		$this->channel = $channel;
	}

	/**
	 * @return GuildUpdatedMessage
	 */
	public function getMessage(): GuildUpdatedMessage {
		return $this->message;
	}



	/**
	 * @return GuildChannel
	 */
	public function getChannel(): GuildChannel {
		return $this->channel;
	}
}


