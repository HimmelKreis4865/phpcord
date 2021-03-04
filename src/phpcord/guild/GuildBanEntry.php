<?php

namespace phpcord\guild;

use phpcord\Discord;
use phpcord\user\User;

class GuildBanEntry {
	/** @var User $user */
	protected $user;
	
	/** @var string | null $reason */
	protected $reason;

	/**
	 * GuildBanEntry constructor.
	 *
	 * @param User $user
	 * @param string|null $reason
	 */
	public function __construct(User $user, ?string $reason) {
		$this->reason = $reason;
		$this->user = $user;
	}

	/**
	 * Returns the user that is banned
	 *
	 * @api
	 *
	 * @return User
	 */
	public function getUser(): User {
		return $this->user;
	}

	/**
	 * Returns the reason or null for unspecified
	 *
	 * @api
	 *
	 * @return string|null
	 */
	public function getReason(): ?string {
		return $this->reason;
	}
	
	public function unban(): bool {
		$guild = Discord::getInstance()->getClient()->getGuild($this->getUser()->getGuildId());
		if ($guild instanceof Guild) return $guild->removeBan($this->getUser()->getId());
		return false;
	}
}