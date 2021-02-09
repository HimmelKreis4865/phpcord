<?php

namespace phpcord\guild;

use phpcord\channel\ChannelType;
use phpcord\http\RestAPIHandler;
use function array_filter;
use function var_dump;

abstract class GuildChannel {

	public $name;

	public $id;

	public $position;

	/** @var GuildPermissionOverwrite[] $permissions */
	public $permissions = [];

	public $guild_id;

	public function __construct(string $guild_id, string $id, string $name, int $position = 0, array $permissions = []) {
		$this->id = $id;
		$this->name = $name;
		$this->position = $position;
		$this->permissions = $permissions;
		$this->guild_id = $guild_id;
	}

	abstract public function getType(): ChannelType;

	/**
	 * @return string
	 */
	public function getId(): string {
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getGuildId(): string {
		return $this->guild_id;
	}

	/**
	 * @return int
	 */
	public function getPosition(): int {
		return $this->position;
	}
	
	public function delete(): bool {
		return !RestAPIHandler::getInstance()->deleteChannel($this->getId())->isFailed();
	}
	
	protected function getModifyData(): array {
		return [
			"name" => $this->name,
			"permission_overwrites" => array_map(function(GuildPermissionOverwrite $key) {
				return $key->encode();
			}, $this->permissions)
		];
	}
	
	public function update(): bool {
		var_dump($this->getModifyData());
		return !RestAPIHandler::getInstance()->updateChannel($this->getId(), $this->getModifyData())->isFailed();
	}
	
	/**
	 * @return array
	 */
	public function getPermissions(): array {
		return $this->permissions;
	}
	
	public function addPermission(GuildPermissionOverwrite $overwrite) {
		$this->permissions[] = $overwrite;
	}
	
	public function removePermission(string $id) {
		foreach ($this->permissions as $key => $overwrite) {
			if ($overwrite->getId() === $id) unset($this->permissions[$key]);
		}
	}
}