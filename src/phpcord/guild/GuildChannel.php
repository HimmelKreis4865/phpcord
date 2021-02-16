<?php

namespace phpcord\guild;

use phpcord\channel\ChannelType;
use phpcord\http\RestAPIHandler;
abstract class GuildChannel {
	/** @var string $name */
	public $name;

	/** @var string $id */
	public $id;

	/** @var int $position */
	public $position;

	/** @var GuildPermissionOverwrite[] $permissions */
	public $permissions = [];

	/** @var string $guild_id */
	public $guild_id;
	
	/**
	 * GuildChannel constructor.
	 *
	 * @param string $guild_id
	 * @param string $id
	 * @param string $name
	 * @param int $position
	 * @param array $permissions
	 */
	public function __construct(string $guild_id, string $id, string $name, int $position = 0, array $permissions = []) {
		$this->id = $id;
		$this->name = $name;
		$this->position = $position;
		$this->permissions = $permissions;
		$this->guild_id = $guild_id;
	}
	
	/**
	 * Returns the channel type of subclasses
	 *
	 * @internal
	 *
	 * @return ChannelType
	 */
	abstract public function getType(): ChannelType;

	/**
	 * Returns the ID of the channel
	 * user's id in a dm
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getId(): string {
		return $this->id;
	}

	/**
	 * Returns the name of the channel
	 * user's name in a dm
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * Returns the GuildID of the channel
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getGuildId(): string {
		return $this->guild_id;
	}

	/**
	 * Returns the position of the channel
	 * Starting from top to bottom
	 *
	 * @api
	 *
	 * @return int
	 */
	public function getPosition(): int {
		return $this->position;
	}
	
	/**
	 * Deletes the channel from the guild and cache
	 *
	 * @api
	 *
	 * @return bool
	 */
	public function delete(): bool {
		return !RestAPIHandler::getInstance()->deleteChannel($this->getId())->isFailed();
	}
	
	/**
	 * Modification data needed for @link GuildChannel::update()
	 *
	 * @internal
	 *
	 * @return array
	 */
	protected function getModifyData(): array {
		return [
			"name" => $this->name,
			"permission_overwrites" => array_map(function(GuildPermissionOverwrite $key) {
				return $key->encode();
			}, $this->permissions)
		];
	}
	
	/**
	 * Updates a GuildChannel in cache and on discord server
	 * This will NOT update channel's position, @see GuildChannel::setPosition() for changing the position
	 *
	 * @api
	 *
	 * @return bool
	 */
	public function update(): bool {
		return !RestAPIHandler::getInstance()->updateChannel($this->getId(), $this->getModifyData())->isFailed();
	}
	
	/**
	 * Returns an array with all permission overwrites made in the channel
	 *
	 * @api
	 *
	 * @return GuildPermissionOverwrite[]
	 */
	public function getPermissions(): array {
		return $this->permissions;
	}
	
	/**
	 * Adds an permission overwrite to the permissions
	 * Needs @see GuildChannel::update() to be updated
	 *
	 * @api
	 *
	 * @param GuildPermissionOverwrite $overwrite
	 */
	public function addPermission(GuildPermissionOverwrite $overwrite) {
		$this->permissions[] = $overwrite;
	}
	/**
	 * Removes an permission overwrite from the permissions
	 * Needs @see GuildChannel::update() to be updated
	 *
	 * @api
	 *
	 * @param GuildPermissionOverwrite|int $id
	 */
	public function removePermission($id) {
		if ($id instanceof GuildPermissionOverwrite) $id = $id->getId();
		foreach ($this->permissions as $key => $overwrite) {
			if ($overwrite->getId() === $id) unset($this->permissions[$key]);
		}
	}
	
	/**
	 * Changes the name of the channel
	 * Needs @see GuildChannel::update() to be updated
	 *
	 * @api
	 *
	 * @param string $name
	 */
	public function setName(string $name): void {
		$this->name = $name;
	}
	
	/**
	 * Changes the position of a channel in a category
	 *
	 * @api
	 *
	 * @param int $position
	 *
	 * @return bool
	 */
	public function setPosition(int $position): bool {
		$result = !RestAPIHandler::getInstance()->setChannelPosition($this->getGuildId(), $this->getId(), $position)->isFailed();
		if ($result) $this->position = $position;
		return $result;
	}
}