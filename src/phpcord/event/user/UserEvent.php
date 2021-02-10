<?php

namespace phpcord\event\user;

use phpcord\event\Event;
use phpcord\user\User;

class UserEvent extends Event {
	/** @var User $user */
	protected $user;
	
	/**
	 * UserEvent constructor.
	 *
	 * @param User $user
	 */
	public function __construct(User $user) {
		$this->user = $user;
	}

	/**
	 * Returns the user instance passed
	 *
	 * @api
	 *
	 * @return User
	 */
	public function getUser(): User {
		return $this->user;
	}
	
	/**
	 * Returns the GuildID of the User
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getGuildId(): string {
		return $this->getUser()->guild_id;
	}
}