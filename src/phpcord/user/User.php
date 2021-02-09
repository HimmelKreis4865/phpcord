<?php

namespace phpcord\user;

use phpcord\Discord;
use phpcord\exception\GuildException;
use phpcord\http\RestAPIHandler;
use phpcord\utils\UserUtils;

class User {
	public $username;

	public $tag;

	public $id;

	public $public_flags;

	public $discriminator = "0000";

	public $bot = false;

	public $avatar;

	public $guild_id;

	public function __construct(string $guild_id, string $id, string $username, string $discriminator, int $public_flags = 0, ?string $avatar = null) {
		$this->id = $id;
		$this->username = $username;
		$this->tag = $username . "#" . strval($discriminator);
		$this->discriminator = $discriminator;
		$this->avatar = $avatar;
		$this->public_flags = $public_flags;
		$this->guild_id = $guild_id;
	}

	public function createMention(): string {
		return "<@" . $this->id . ">";
	}

	/**
	 * @param string $extension
	 * @param int $size
	 *
	 * @return string|null
	 */
	public function getAvatarURL(string $extension = "png", int $size = 1024): string {
		if (!in_array($extension, UserUtils::AVATAR_SUPPORTED_EXTENSIONS)) $extension = "png";
		return UserUtils::AVATAR_URL_PATH . $this->id . "/" . $this->avatar . "." . $extension . "?size=" . $size;
	}

	/**
	 * @return string
	 */
	public function getGuildId(): string {
		return $this->guild_id;
	}

	/**
	 * @return string
	 */
	public function getId(): string {
		return $this->id;
	}

	/**
	 * @return string|null
	 */
	public function getAvatar(): ?string {
		return $this->avatar;
	}

	/**
	 * @return string
	 */
	public function getDiscriminator(): string {
		return $this->discriminator;
	}

	/**
	 * @return int
	 */
	public function getPublicFlags(): int {
		return $this->public_flags;
	}

	/**
	 * @return string
	 */
	public function getTag(): string {
		return $this->tag;
	}

	/**
	 * @return string
	 */
	public function getUsername(): string {
		return $this->username;
	}
	
	public function ban(?string $reason = null, int $messageDeleteDays = 0) {
		if (($instance = Discord::getInstance()) === null) throw new GuildException("Cannot ban a Member with a not initialized Client!");
		if (($guild = $instance->getClient()->getGuild($this->getGuildId())) === null) throw new GuildException("Couldn't find a registered Guild " . $this->getGuildId());
		$guild->addBan($this, $reason, $messageDeleteDays);
	}
	
	public function kick(?string $reason = null): bool {
		return !RestAPIHandler::getInstance()->removeMember($this->getGuildId(), $this->getId(), $reason)->isFailed();
	}
}


