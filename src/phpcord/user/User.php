<?php

namespace phpcord\user;

use phpcord\Discord;
use phpcord\exception\GuildException;
use phpcord\http\RestAPIHandler;
use phpcord\utils\UserUtils;
use phpcord\task\Promise;

class User {
	/** @var string $username */
	public $username;

	/** @var string $tag */
	public $tag;

	/** @var string $id */
	public $id;

	/** @var int $public_flags */
	public $public_flags;

	/** @var string $discriminator */
	public $discriminator = "0000";

	/** @var bool $bot */
	public $bot = false;

	/** @var string|null $avatar */
	public $avatar;

	/** @var string $guild_id */
	public $guild_id;
	
	/**
	 * User constructor.
	 *
	 * @param string $guild_id
	 * @param string $id
	 * @param string $username
	 * @param string $discriminator
	 * @param int $public_flags
	 * @param string|null $avatar
	 * @param bool $bot
	 */
	public function __construct(string $guild_id, string $id, string $username, string $discriminator, int $public_flags = 0, ?string $avatar = null, bool $bot = false) {
		$this->id = $id;
		$this->username = $username;
		$this->tag = $username . "#" . strval($discriminator);
		$this->discriminator = $discriminator;
		$this->avatar = $avatar;
		$this->public_flags = $public_flags;
		$this->guild_id = $guild_id;
		$this->bot = $bot;
	}
	
	/**
	 * Creates a (hopefully) valid mention
	 *
	 * @api
	 *
	 * @return string
	 */
	public function createMention(): string {
		return "<@" . $this->id . ">";
	}

	/**
	 * Returns the avatar url built by id + / + avatar url hash
	 *
	 * @api
	 *
	 * @param string $extension
	 * @param int $size
	 * @param bool $returnDefault
	 *
	 * @return string|null
	 */
	public function getAvatarURL(string $extension = "png", int $size = 1024, bool $returnDefault = true): ?string {
		if ($this->avatar === null) {
			if (!$returnDefault) return null;
			return self::getDefaultAvatar($this->getDiscriminator());
		}
		if (!in_array($extension, UserUtils::AVATAR_SUPPORTED_EXTENSIONS)) $extension = "png";
		return UserUtils::AVATAR_URL_PATH . $this->id . "/" . $this->avatar . "." . $extension . "?size=" . $size;
	}
	
	/**
	 * Returns the url for the default avatar
	 *
	 * @internal
	 *
	 * @param int $discriminator
	 *
	 * @return string
	 */
	public static function getDefaultAvatar(int $discriminator): string {
		return "https://cdn.discordapp.com/embed/avatars/" . ($discriminator % 5) . ".png";
	}

	/**
	 * Returns the GuildID of the User
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getGuildId(): string {
		return $this->guild_id;
	}

	/**
	 * Returns the ID of the User
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getId(): string {
		return $this->id;
	}

	/**
	 * Returns the avatar hash or null if the user's having the default avatar
	 *
	 * @api
	 *
	 * @return string|null
	 */
	public function getAvatar(): ?string {
		return $this->avatar;
	}

	/**
	 * Returns the discriminator (#0000) of the user, must be returned as string since int of 0001 would be 1 which is an invalid discriminator
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getDiscriminator(): string {
		return $this->discriminator;
	}

	/**
	 * Returns the bitwise public flags of the user
	 *
	 * @api
	 *
	 * @return int
	 */
	public function getPublicFlags(): int {
		return $this->public_flags;
	}

	/**
	 * Returns the tag of the user which is username + # + discriminator
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getTag(): string {
		return $this->tag;
	}

	/**
	 * Returns the username of a user
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getUsername(): string {
		return $this->username;
	}
	
	/**
	 * Bans the user from the guild he is on
	 *
	 * @api
	 *
	 * @param string|null $reason
	 * @param int $messageDeleteDays
	 */
	public function ban(?string $reason = null, int $messageDeleteDays = 0) {
		if (($instance = Discord::getInstance()) === null) throw new GuildException("Cannot ban a Member with a not initialized Client!");
		if (($guild = $instance->getClient()->getGuild($this->getGuildId())) === null) throw new GuildException("Couldn't find a registered Guild " . $this->getGuildId());
		$guild->addBan($this, $reason, $messageDeleteDays);
	}
	
	/**
	 * Kicks the user from the guild he is on
	 *
	 * @api
	 *
	 * @param string|null $reason
	 * @return Promise
	 */
	public function kick(?string $reason = null): Promise {
		return RestAPIHandler::getInstance()->removeMember($this->getGuildId(), $this->getId(), $reason);
	}
	
	/**
	 * Returns whether this member is a human or not
	 *
	 * @api
	 *
	 * @return bool
	 */
	public function isHuman(): bool {
		return !$this->bot;
	}
	
	/**
	 * Returns whether this is a bot or not, opposite to @see isHuman()
	 *
	 * @api
	 *
	 * @return bool
	 */
	public function isBot(): bool {
		return $this->bot;
	}
}