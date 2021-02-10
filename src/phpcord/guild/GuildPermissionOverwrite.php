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
	 * @param string $id the id of the role / member
	 * @param Permission $permission a permission for overwriting
	 */
	public function __construct(string $id, Permission $permission) {
		$this->id = $id;
		$this->permission = $permission;
	}
	
	/**
	 * Returns the type of the overwrite, either 0 => role or 1 => member
	 *
	 * @api
	 *
	 * @return int
	 */
	abstract public function getType(): int;
	
	/**
	 * Encodes the overwrite to an array that can be used to communicate with RESTAPI
	 *
	 * @api
	 *
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
	 * Returns the id of the role / member
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getId(): string {
		return $this->id;
	}
}