<?php

namespace phpcord\event\guild;

use phpcord\event\Event;

/**
 * Class GuildDeleteEvent
 *
 * This event is not only called when a guild was deleted,
 * More often when the bot gets kicked out of it or it becomes unavailable for him
 */
class GuildDeleteEvent extends Event {
	/** @var string $id */
	protected $id;
	
	/** @var bool $kicked */
	protected $kicked = false;
	
	/**
	 * GuildDeleteEvent constructor.
	 *
	 * @param string $id
	 * @param bool $kicked
	 */
	public function __construct(string $id, bool $kicked = false) {
		$this->id = $id;
		$this->kicked = $kicked;
	}
	
	/**
	 * Returns the id of the guild
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getId(): string {
		return $this->id;
	}
	
	/**
	 * Returns whether the guild became unavailable due to kick or not
	 *
	 * @api
	 *
	 * @return bool
	 */
	public function isKicked(): bool {
		return $this->kicked;
	}
}