<?php

namespace phpcord\guild;

use phpcord\Discord;
use phpcord\http\RestAPIHandler;
use phpcord\user\User;
use function array_map;
use function is_numeric;

class GuildMember extends User {

	public $roles = [];

	public $bot = false;

	public $nick = "";

	public $joined_at = "";

	public $premium_since = null;

	public function __construct(string $guild_id, string $id, string $username, string $discriminator, array $roles, bool $bot = false, string $nick = "", int $public_flags = 0, ?string $avatar = null, string $joined_at = "", ?string $premium_since = null) {
		parent::__construct($guild_id, $id, $username, $discriminator, $public_flags, $avatar);
		$this->roles = $roles;
		$this->bot = $bot;
		$this->nick = $nick;
		$this->joined_at = $joined_at;
		$this->premium_since = $premium_since;
	}

	public function isHuman(): bool {
		return !$this->bot;
	}

	public function asUser(): User {
		return new User($this->guild_id, $this->id, $this->username, $this->discriminator, $this->public_flags, $this->avatar);
	}

	/**
	 * @return string[]
	 */
	public function getRolesIds(): array {
		return $this->roles;
	}

	/**
	 * @return GuildRole[]
	 */
	public function getRoles(): array {
		$guildId = $this->getGuildId();
		return array_filter(array_map(function($key) use ($guildId) {
			return Discord::$lastInstance->getClient()->getGuild($guildId)->getRole($key);
		}, $this->roles), function($key) {
			return !is_null($key);
		});
	}

	public function hasPermission($permission): bool {
		if (Discord::getInstance()->getClient()->getGuild($this->guild_id)->isOwner($this)) return true;
		foreach ($this->getRoles() as $role) {
			if ($role->hasPermission($permission)) return true;
		}
		return false;
	}
	
	public function getGuild(): ?Guild {
		return Discord::getInstance()->getClient()->getGuild($this->getGuildId());
	}
	
	public function hasRole($role): bool {
		if ($role instanceof GuildRole) $role = $role->getId();
		return isset($this->roles[$role]);
	}
	
	public function addRole($role): bool {
		if ($role instanceof GuildRole) $role = $role->getId();
		if ($this->hasRole($role)) return false;
		$this->roles[] = $role;
		return !RestAPIHandler::getInstance()->addRoleToUser($this->getGuildId(), $this->getId(), $role)->isFailed();
	}
	
	public function removeRole($role): bool {
		if ($role instanceof GuildRole) $role = $role->getId();
		if (!$this->hasRole($role)) return false;
		if (isset($this->roles[$role])) unset($this->roles[$role]);
		return !RestAPIHandler::getInstance()->removeRoleFromUser($this->getGuildId(), $this->getId(), $role)->isFailed();
	}
}