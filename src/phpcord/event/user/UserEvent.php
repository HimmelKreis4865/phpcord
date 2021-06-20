<?php

namespace phpcord\event\user;

use phpcord\Discord;
use phpcord\event\Event;
use phpcord\guild\Guild;
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
	
	/**
	 * Returns the guild the player was removed from
	 *
	 * @api
	 *
	 * @return Guild|null
	 */
	public function getGuild(): ?Guild {
		return Discord::getInstance()->getClient()->getGuild($this->getGuildId());
	}
}