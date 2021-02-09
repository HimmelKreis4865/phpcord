<?php

namespace phpcord\event\user;

use phpcord\event\Event;
use phpcord\user\User;

class UserEvent extends Event {
	protected $user;

	public function __construct(User $user) {
		$this->user = $user;
	}

	/**
	 * @return User
	 */
	public function getUser(): User {
		return $this->user;
	}

	public function getGuildId(): string {
		return $this->getUser()->guild_id;
	}
}


