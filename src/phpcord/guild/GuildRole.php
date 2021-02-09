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
	 * @return string
	 */
	public function getId(): string {
		return $this->id;
	}

	/**
	 * @return int|null
	 */
	public function getPermissions(): ?int {
		return $this->permissions;
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @return int|string
	 */
	public function getColor() {
		return $this->color;
	}

	/**
	 * @return int
	 */
	public function getPosition(): int {
		return $this->position;
	}

	/**
	 * @return bool
	 */
	public function isManaged(): bool {
		return $this->managed;
	}

	/**
	 * @return bool
	 */
	public function isMentionable(): bool {
		return $this->mentionable;
	}

	/**
	 * @return string
	 */
	public function getGuildId(): string {
		return $this->guildId;
	}

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
	 * @param string $name
	 */
	public function setName(string $name): void {
		$this->name = $name;
	}
	
	/**
	 * @param int|string|ColorUtils|array|RGB $color
	 */
	public function setColor($color): void {
		$this->color = ColorUtils::createFromCustomData($color)->decimal;
	}
	
	public function addPermission($permission) {
		if ($permission instanceof Permission) $permission = $permission->toInt();
		if (!is_numeric($permission)) throw new \InvalidArgumentException("Could not set permission $permission");
		$this->permissions |= $permission;
	}
	
	public function removePermission($permission) {
		if ($permission instanceof Permission) $permission = $permission->toInt();
		if (!is_numeric($permission)) throw new \InvalidArgumentException("Could not set permission $permission");
		$this->permissions &= ~$permission;
	}
	
	/**
	 * @param bool $mentionable
	 */
	public function setMentionable(bool $mentionable): void {
		$this->mentionable = $mentionable;
	}
	
	/**
	 * @param bool $managed
	 */
	public function setManaged(bool $managed): void {
		$this->managed = $managed;
	}
	
	public function update(): bool {
		return !RestAPIHandler::getInstance()->modifyRole($this->getGuildId(), $this->getId(), $this->getName(), $this->getColor(), $this->getPermissions(), $this->isManaged())->isFailed();
	}
	
	public function delete(): bool {
		return $this->getGuild()->deleteRole($this->getId());
	}
	
	public function getGuild(): ?Guild {
		return Discord::getInstance()->getClient()->getGuild($this->getGuildId());
	}
	
	protected function hasPermissionInt(int $permission): bool {
		return (($this->permissions & $permission) === $permission);
	}
}