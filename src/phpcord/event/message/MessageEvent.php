<?php

namespace phpcord\event\message;

use phpcord\channel\BaseTextChannel;
use phpcord\Discord;
use phpcord\event\Event;
use phpcord\guild\Guild;
use phpcord\guild\GuildMessage;
use phpcord\guild\GuildChannel;

class MessageEvent extends Event {
	/** @var GuildMessage $message */
	protected $message;
	/** @var BaseTextChannel $channel */
    protected $channel;

	/**
	 * MessageEvent constructor.
	 *
	 * @param GuildMessage $message
	 * @param BaseTextChannel $channel
	 */
	public function __construct(GuildMessage $message, BaseTextChannel $channel) {
		$this->message = $message;
		$this->channel = $channel;
	}

	/**
	 * Returns the sent message
	 *
	 * @api
	 *
	 * @return GuildMessage
	 */
	public function getMessage(): GuildMessage {
		return $this->message;
	}
	
	/**
	 * Returns the channel the message was sent in
	 *
	 * @return BaseTextChannel
	 */
	public function getChannel(): BaseTextChannel {
		return $this->channel;
	}
}



