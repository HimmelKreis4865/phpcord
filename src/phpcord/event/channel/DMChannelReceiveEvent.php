<?php

namespace phpcord\event\channel;

use phpcord\channel\DMChannel;
use phpcord\event\Event;

/**
 * Class DMChannelReceiveEvent
 *
 * Called when creating a dm or receiving a dm message
 *
 * @package phpcord\event\channel
 */
class DMChannelReceiveEvent extends Event {
	/** @var DMChannel $channel */
	protected $channel;
	
	/**
	 * DMChannelReceiveEvent constructor.
	 *
	 * @param DMChannel $channel
	 */
	public function __construct(DMChannel $channel) {
		$this->channel = $channel;
	}
	
	/**
	 * Returns the dm channel involved
	 *
	 * @api
	 *
	 * @return DMChannel
	 */
	public function getChannel(): DMChannel {
		return $this->channel;
	}
}