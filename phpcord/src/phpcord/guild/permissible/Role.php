<?php

/*
 *         .__                                       .___
 * ______  |  |__  ______    ____   ____ _______   __| _/
 * \____ \ |  |  \ \____ \ _/ ___\ /  _ \\_  __ \ / __ |
 * |  |_> >|   Y  \|  |_> >\  \___(  <_> )|  | \// /_/ |
 * |   __/ |___|  /|   __/  \___  >\____/ |__|   \____ |
 * |__|         \/ |__|         \/                    \/
 *
 *
 * This library is developed by HimmelKreis4865 © 2022
 *
 * https://github.com/HimmelKreis4865/phpcord
 */

namespace phpcord\guild\permissible;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use JsonSerializable;
use phpcord\async\completable\Completable;
use phpcord\Discord;
use phpcord\guild\Guild;
use phpcord\guild\permissible\data\BasePermissionData;
use phpcord\guild\permissible\data\IPermissionData;
use phpcord\http\RestAPI;
use phpcord\image\Icon;
use phpcord\utils\CDN;
use phpcord\utils\Color;
use phpcord\utils\Utils;
use function var_dump;

class Role implements JsonSerializable {
	use PermissionUpdateTrait;
	
	/** @var BasePermissionData $permissionData */
	private BasePermissionData $permissionData;
	
	/**
	 * @param int $id
	 * @param string $name
	 * @param int $guildId
	 * @param Icon|null $icon
	 * @param Color $color
	 * @param int $permission
	 * @param int $position
	 * @param bool $managed
	 * @param bool $mentionable
	 */
	#[Pure] public function __construct(private int $id, private string $name, private int $guildId, private ?Icon $icon, private Color $color, int $permission, private int $position, private bool $managed = false, private bool $mentionable = false) {
		$this->permissionData = new BasePermissionData($permission);
	}
	
	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
	}
	
	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}
	
	/**
	 * @return int
	 */
	public function getGuildId(): int {
		return $this->guildId;
	}
	
	public function getGuild(): ?Guild {
		return Discord::getInstance()->getClient()->getGuilds()->get($this->getGuildId());
	}
	
	/**
	 * @return Icon|null
	 */
	public function getIcon(): ?Icon {
		return $this->icon;
	}
	
	/**
	 * @return Color
	 */
	public function getColor(): Color {
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
	 * @param int $position
	 *
	 * @return Completable<array<Role>> Returns all roles in their new order
	 */
	public function setPosition(int $position): Completable {
		return RestAPI::getInstance()->setRolePosition($this->getGuildId(), $this->getId(), $position);
	}
	
	/**
	 * @param string|null $reason
	 *
	 * @return Completable
	 */
	public function delete(string $reason = null): Completable {
		return RestAPI::getInstance()->deleteRole($this->getGuildId(), $this->getId(), $reason);
	}
	
	public function getPermissionData(): IPermissionData {
		return $this->permissionData;
	}
	
	protected function syncToDiscord(): Completable {
		return Completable::sync(); // todo
	}
	
	public function replaceBy(Role $role): void {
		$this->name = $role->getName();
		$this->permissionData->getPermission()->setPermissionBit($role->permissionData->getPermission()->getPermissionBit());
		$this->icon = $role->getIcon();
		$this->managed = $role->isManaged();
		$this->mentionable = $role->isMentionable();
		$this->color = $role->getColor();
		$this->position = $role->getPosition();
	}
	
	/**
	 * @todo icon
	 *
	 * @return array
	 */
	#[Pure] #[ArrayShape(['color' => "int", 'permissions' => "int", 'name' => "string", 'mentionable' => "bool"])] public function jsonSerialize(): array {
		return [
			'color' => $this->color->dec(),
			'permissions' => $this->permissionData->getPermission()->getPermissionBit(),
			'name' => $this->name,
			'mentionable' => $this->mentionable
		];
	}
	
	public static function fromArray(array $array): ?Role {
		if (!Utils::contains($array, 'guild_id', 'id', 'name', 'permissions', 'color', 'position')) return null;
		return new Role($array['id'], $array['name'], $array['guild_id'], (($array['icon'] ?? false) ? new Icon($array['icon'], CDN::ROLE_ICON($array['id'], $array['icon'])) : null), Color::fromInt($array['color']), $array['permissions'], $array['position'], $array['managed'] ?? true, $array['mentionable'] ?? false);
	}
}