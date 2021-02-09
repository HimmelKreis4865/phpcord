<?php

namespace phpcord\guild;

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
	 * @return User
	 */
	public function getUser(): User {
		return $this->user;
	}

	/**
	 * @return string|null
	 */
	public function getReason(): ?string {
		return $this->reason;
	}
}


