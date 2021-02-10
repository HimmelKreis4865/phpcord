<?php

namespace phpcord\guild;

use phpcord\channel\embed\ColorUtils;
use phpcord\channel\embed\components\RGB;
use phpcord\Discord;
use phpcord\http\RestAPIHandler;
use phpcord\utils\Permission;
use phpcord\utils\PermissionIds;
use function array_values;
use function is_numeric;
use function is_string;

class GuildRole {
	/** @var string $name */
	public $name;

	/** @var int|null $permissions */
	public $permissions = 104320577;

	/** @var bool $mentionable */
	public $mentionable = false;

	/** @var bool $managed */
	public $managed = false;

	/** @var string $id */
	public $id;

	/** @var int|string $color */
	public $color = 0;

	/** @var int $position */
	public $position;
	
	/** @var string $guildId */
	public $guildId;

	/**
	 * GuildRole constructor.
	 *
	 * @param string $guildId
	 * @param string $name
	 * @param string $id
	 * @param int $position
	 * @param int|null $permissions
	 * @param int|string $color
	 * @param bool $mentionable
	 * @param bool $managed
	 */
	public function __construct(string $guildId, string $name, string $id, int $position = 0, ?int $permissions = 0, $color = 0, bool $mentionable = true, bool $managed = true) {
		$this->name = $name;
		$this->id = $id;
		$this->guildId = $guildId;
		$this->position = $position;
		$this->permissions = $permissions;
		$this->color = $color;
		$this->mentionable = $mentionable;
		$this->managed = $managed;
	}

	/**
	 * Returns the ID of the role
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getId(): string {
		return $this->id;
	}

	/**
	 * Returns the bitwise permissions
	 *
	 * @api
	 *
	 * @return int|null
	 */
	public function getPermissions(): ?int {
		return $this->permissions;
	}

	/**
	 * Returns the name of the role
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * Returns the color code of the role
	 *
	 * @api
	 *
	 * @return int|string
	 */
	public function getColor() {
		return $this->color;
	}

	/**
	 * Returns the position in the role list
	 *
	 * @api
	 *
	 * @return int
	 */
	public function getPosition(): int {
		return $this->position;
	}

	/**
	 * Return whether the role is managed or not
	 *
	 * @todo what exactly is that?
	 *
	 * @api
	 *
	 * @return bool
	 */
	public function isManaged(): bool {
		return $this->managed;
	}

	/**
	 * Returns whether the role is mentionable for anyone or not
	 *
	 * @api
	 *
	 * @return bool
	 */
	public function isMentionable(): bool {
		return $this->mentionable;
	}

	/**
	 * Returns the GuildID of the role
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getGuildId(): string {
		return $this->guildId;
	}
	
	/**
	 * Returns whether the role has a bitwise permission or not
	 *
	 * @api
	 *
	 * @param int|string $permission
	 *
	 * @return bool
	 */
	public function hasPermission($permission): bool {
		$perm = null;
		if (is_string($permission)) {
			if (!isset(PermissionIds::PERMISSIONS[$permission])) return false;
			$perm = PermissionIds::PERMISSIONS[$permission];
		}
		if (!is_int($perm) or !in_array($perm, array_values(PermissionIds::PERMISSIONS))) return false;
		if ($this->hasPermissionInt(ADMINISTRATOR)) return true;
		return $this->hasPermissionInt($permission);
	}
	
	/**
	 * Changes the name to the target name
	 * Needs @see GuildRole::update() to be modified on guild
	 *
	 * @api
	 *
	 * @param string $name
	 */
	public function setName(string $name): void {
		$this->name = $name;
	}
	
	/**
	 * Changes the color to another one (black if it's an invalid color)
	 * Needs @see GuildRole::update() to be modified on guild
	 *
	 * @api
	 *
	 * @param int|string|ColorUtils|array|RGB $color
	 */
	public function setColor($color): void {
		$this->color = ColorUtils::createFromCustomData($color)->decimal;
	}
	
	/**
	 * Adds a permission to the role
	 * Needs @see GuildRole::update() to be modified on guild
	 *
	 * @api
	 *
	 * @param int|Permission $permission
	 */
	public function addPermission($permission) {
		if ($permission instanceof Permission) $permission = $permission->toInt();
		if (!is_numeric($permission)) throw new \InvalidArgumentException("Could not set permission $permission");
		$this->permissions |= $permission;
	}
	/**
	 * Removes a permission from the role
	 * Needs @see GuildRole::update() to be modified on guild
	 *
	 * @api
	 *
	 * @param int|Permission $permission
	 */
	public function removePermission($permission) {
		if ($permission instanceof Permission) $permission = $permission->toInt();
		if (!is_numeric($permission)) throw new \InvalidArgumentException("Could not set permission $permission");
		$this->permissions &= ~$permission;
	}
	
	/**
	 * Set this role mentionable or not
	 * Needs @see GuildRole::update() to be modified on guild
	 *
	 * @api
	 *
	 * @param bool $mentionable
	 */
	public function setMentionable(bool $mentionable): void {
		$this->mentionable = $mentionable;
	}
	
	/**
	 * Set this role managed or not
	 * Needs @see GuildRole::update() to be modified on guild
	 *
	 * @todo: what is that for?
	 *
	 * @api
	 *
	 * @param bool $managed
	 */
	public function setManaged(bool $managed): void {
		$this->managed = $managed;
	}
	
	/**
	 * Updates the role to guild and cache
	 * Returns false on failure
	 *
	 * @api
	 *
	 * @return bool
	 */
	public function update(): bool {
		return !RestAPIHandler::getInstance()->modifyRole($this->getGuildId(), $this->getId(), $this->getName(), $this->getColor(), $this->getPermissions(), $this->isManaged())->isFailed();
	}
	
	/**
	 * Deletes the role from guild and cache
	 * Returns false on failure
	 *
	 * @api
	 *
	 * @return bool
	 */
	public function delete(): bool {
		return $this->getGuild()->deleteRole($this->getId());
	}
	
	/**
	 * Tries to get the role from cache, won't fetch it
	 *
	 * @api
	 *
	 * @return Guild|null
	 */
	public function getGuild(): ?Guild {
		return Discord::getInstance()->getClient()->getGuild($this->getGuildId());
	}
	
	/**
	 * Returns whether the role has a bitwise permission or not
	 *
	 * @internal
	 *
	 * @param int $permission
	 *
	 * @return bool
	 */
	protected function hasPermissionInt(int $permission): bool {
		return (($this->permissions & $permission) === $permission);
	}
}