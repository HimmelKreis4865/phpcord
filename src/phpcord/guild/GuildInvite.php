<?php

namespace phpcord\guild;

use phpcord\channel\BaseTextChannel;
use phpcord\Discord;
use phpcord\user\User;

class GuildInvite {
	
	public const TYPE_STREAM = 1;
	
	/** @var string $code */
	protected $code;
	
	/** @var IncompleteGuild|null $guild note: don't mix Guild and IncompleteGuild up! */
	protected $guild;
	
	/** @var IncompleteChannel|null $channel */
	protected $channel = null;
	
	/** @var User|null $inviter */
	protected $inviter = null;
	
	/** @var User|null $target_user */
	protected $target_user = null;
	
	/** @var int $target_user_type */
	protected $target_user_type = self::TYPE_STREAM;
	
	/** @var int|null $approximate_presence_count */
	protected $approximate_presence_count = null;
	
	/** @var int|null $approximate_member_count */
	protected $approximate_member_count = null;
	
	/**
	 * GuildInvite constructor.
	 *
	 * @param string $code
	 * @param IncompleteGuild|null $guild
	 * @param IncompleteChannel|null $channel
	 * @param User|null $inviter
	 * @param User|null $target_user
	 * @param int $target_user_type
	 * @param int|null $approximate_presence_count
	 * @param int|null $approximate_member_count
	 */
	public function __construct(string $code, ?IncompleteGuild $guild = null, ?IncompleteChannel $channel = null, ?User $inviter = null, ?User $target_user = null, int $target_user_type = self::TYPE_STREAM, ?int $approximate_presence_count = null, ?int $approximate_member_count = null) {
		$this->code = $code;
		$this->guild = $guild;
		$this->channel = $channel;
		$this->inviter = $inviter;
		$this->target_user = $target_user;
		$this->target_user_type = $target_user_type;
		$this->approximate_presence_count = $approximate_presence_count;
		$this->approximate_member_count = $approximate_member_count;
	}
	
	/**
	 * Returns the guild the invite was made for, includes just some important information about it
	 *
	 * @api
	 *
	 * @return IncompleteGuild|null
	 */
	public function getGuild(): ?IncompleteGuild {
		return $this->guild;
	}
	
	/**
	 * Approximate count of all members of the guild
	 *
	 * @api
	 *
	 * @return int|null
	 */
	public function getApproximateMemberCount(): ?int {
		return $this->approximate_member_count;
	}
	
	/**
	 * Approximate count of all online members of the guild
	 *
	 * @api
	 *
	 * @return int|null
	 */
	public function getApproximatePresenceCount(): ?int {
		return $this->approximate_presence_count;
	}
	
	/**
	 * Returns a channel with only a few features
	 *
	 * @api
	 *
	 * @return IncompleteChannel|null
	 */
	public function getChannel(): ?IncompleteChannel {
		return $this->channel;
	}
	
	/**
	 * Returns the invite code
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getCode(): string {
		return $this->code;
	}
	
	/**
	 * Returns the inviter or null if there is none
	 *
	 * @api
	 *
	 * @return User|null
	 */
	public function getInviter(): ?User {
		return $this->inviter;
	}
	
	/**
	 * Returns the target user this invite was made for, or null if no one specified
	 *
	 * @api
	 *
	 * @return User|null
	 */
	public function getTargetUser(): ?User {
		return $this->target_user;
	}
	
	/**
	 * Returns the type of the target user (right now only @see GuildInvite::TYPE_STREAM [1] is possible)
	 *
	 * @api
	 *
	 * @return int
	 */
	public function getTargetUserType(): int {
		return $this->target_user_type;
	}
	
	/**
	 * Tries to delete the invite, returns false on failure
	 *
	 * @api
	 *
	 * @return bool
	 */
	public function delete(): bool {
		return Discord::getInstance()->getClient()->deleteInvite($this->getCode());
	}
}