<?php

namespace phpcord\guild;

use phpcord\Discord;
use phpcord\user\User;

class GuildEmoji extends Emoji {
	/** @var User|null $creator */
	protected $creator;
	
	/** @var bool $require_colons */
	protected $require_colons = true;
	
	/** @var bool $managed */
	protected $managed = false;
	
	/** @var bool $animated */
	protected $animated = false;
	
	/** @var bool $available */
	protected $available = false;
	
	/** @var string $guildId */
	protected $guildId;
	
	/**
	 * GuildEmoji constructor.
	 *
	 * @param string $guildId
	 * @param string $name
	 * @param string|null $id
	 * @param User|null $creator
	 * @param bool $require_colons
	 * @param bool $managed
	 * @param bool $animated
	 * @param bool $available
	 */
	public function __construct(string $guildId, string $name, ?string $id = null, User $creator = null, bool $require_colons = true, bool $managed = false, bool $animated = false, bool $available = true) {
		parent::__construct($name, $id);
		$this->guildId = $guildId;
		$this->creator = $creator;
		$this->require_colons = $require_colons;
		$this->managed = $managed;
		$this->animated = $animated;
		$this->available = $available;
	}
	
	/**
	 * Returns the Creator of the Emoji
	 *
	 * @api
	 *
	 * @return User|null
	 */
	public function getCreator(): ?User {
		return $this->creator;
	}
	
	/**
	 * Returns whether this emoji is animated or not
	 *
	 * @api
	 *
	 * @return bool
	 */
	public function isAnimated(): bool {
		return $this->animated;
	}
	
	/**
	 * Returns whether this emoji is managed or not
	 *
	 * @api
	 *
	 * @return bool
	 */
	public function isManaged(): bool {
		return $this->managed;
	}
	
	/**
	 * Returns whether the Emoji is available or not - might be false due to loss of server boost
	 *
	 * @api
	 *
	 * @return bool
	 */
	public function isAvailable(): bool {
		return $this->available;
	}
	
	/**
	 * Returns whether it requires colons or not
	 *
	 * @api
	 *
	 * @return bool
	 */
	public function requireColons(): bool {
		return $this->require_colons;
	}
	
	/**
	 * Returns the GuildID of the Emoji
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getGuildId(): string {
		return $this->guildId;
	}
	
	/**
	 * Tries to fetch the guild from the cache
	 *
	 * @api
	 *
	 * @return Guild|null
	 */
	public function getGuild(): ?Guild {
		return Discord::getInstance()->getClient()->getGuild($this->getGuildId());
	}
}