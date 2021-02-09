<?php

namespace phpcord\event\channel;

use phpcord\event\Event;
use phpcord\guild\GuildChannel;

class ChannelEvent extends Event {
	/** @var GuildChannel $channel */
	protected $channel;

	public function __construct(GuildChannel $channel) {
		$this->channel = $channel;
	}

	/**
	 * @return GuildChannel
	 */
	public function getChannel(): GuildChannel {
		return $this->channel;
	}
}


