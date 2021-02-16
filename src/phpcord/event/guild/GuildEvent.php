<?php

namespace phpcord\event\guild;

use phpcord\event\Event;
use phpcord\guild\Guild;

class GuildEvent extends Event {
	/** @var Guild $guild */
	protected $guild;
	
	/**
	 * GuildEvent constructor.
	 *
	 * @param Guild $guild
	 */
	public function __construct(Guild $guild) {
		$this->guild = $guild;
	}
	
	/**
	 * Returns the instance of the guild that called the event
	 *
	 * @api
	 *
	 * @return Guild
	 */
	public function getGuild(): Guild {
		return $this->guild;
	}
}