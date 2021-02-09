<?php

namespace phpcord\guild;

use phpcord\utils\Permission;

abstract class GuildPermissionOverwrite {
	
	public const TYPE_ROLE = 0;
	
	public const TYPE_MEMBER = 1;
	
	/** @var string $id */
	protected $id;
	
	/** @var Permission $permission */
	protected $permission;
	
	/**
	 * GuildPermissionOverwrite constructor.
	 *
	 * @param string $id
	 * @param Permission $permission
	 */
	public function __construct(string $id, Permission $permission) {
		$this->id = $id;
		$this->permission = $permission;
	}
	
	abstract public function getType(): int;
	
	/**
	 * @return array
	 */
	public function encode(): array {
		return [
			"id" => $this->id,
			"type" => $this->getType(),
			"allow" => $this->permission->getAllow(),
			"deny" => $this->permission->getDeny()
		];
	}
	
	/**
	 * @return string
	 */
	public function getId(): string {
		return $this->id;
	}
}