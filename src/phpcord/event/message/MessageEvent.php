<?php

namespace phpcord\event\message;

use phpcord\channel\BaseTextChannel;
use phpcord\channel\Channel;
use phpcord\channel\DMChannel;
use phpcord\event\Event;
use phpcord\guild\GuildMessage;

class MessageEvent extends Event {
	/** @var GuildMessage $message */
	protected $message;
	/** @var BaseTextChannel|DMChannel $channel */
    protected $channel;

	/**
	 * MessageEvent constructor.
	 *
	 * @param GuildMessage $message
	 * @param BaseTextChannel|DMChannel $channel
	 */
	public function __construct(GuildMessage $message, $channel) {
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
	 * @api
	 *
	 * @return BaseTextChannel|DMChannel
	 */
	public function getChannel() {
		return $this->channel;
	}
}