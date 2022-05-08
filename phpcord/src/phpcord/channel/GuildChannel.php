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

namespace phpcord\channel;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use JsonSerializable;
use phpcord\async\completable\Completable;
use phpcord\channel\overwrite\PermissionOverwrite;
use phpcord\Discord;
use phpcord\guild\Guild;
use phpcord\http\RestAPI;
use phpcord\image\ImageData;
use phpcord\utils\Collection;
use phpcord\utils\Factory;
use phpcord\utils\Utils;

abstract class GuildChannel extends Channel implements JsonSerializable {
	
	/**
	 * A collection storing all the permission overwrites of the channel (member/role)
	 *
	 * @var Collection $overwrites
	 * @phpstan-var Collection<PermissionOverwrite>
	 */
	private Collection $overwrites;
	
	public function __construct(int $id, private int $guildId, private string $name, private int $position, private ?int $parentId, array $overwrites) {
		parent::__construct($id);
		$this->overwrites = new Collection(Factory::createOverwriteArray($this, $overwrites));
	}
	
	/**
	 * @return Collection
	 */
	public function getOverwrites(): Collection {
		return $this->overwrites;
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
	 * @return int
	 */
	public function getPosition(): int {
		return $this->position;
	}
	
	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}
	
	/**
	 * @return int|null
	 */
	public function getParentId(): ?int {
		return $this->parentId;
	}
	
	/**
	 * @param string $name
	 *
	 * @return Completable
	 */
	public function setName(string $name): Completable {
		$this->name = $name;
		return $this->triggerUpdate();
	}
	
	/**
	 * @param int $position
	 *
	 * @return Completable
	 */
	public function setPosition(int $position): Completable {
		$this->position = $position;
		return $this->triggerPositionUpdate($position);
	}
	
	/**
	 * @param int|null $parentId
	 * @param bool $syncPermissions If set to true, the channel's permission will be set to the permissions set for the new parent
	 *
	 * @return Completable
	 */
	public function setParentId(?int $parentId, bool $syncPermissions = null): Completable {
		$this->parentId = $parentId;
		return $this->triggerPositionUpdate(null, $parentId, $syncPermissions);
	}
	
	/**
	 * @internal
	 *
	 * @param int|null $position
	 * @param int|null $parentId
	 * @param bool|null $syncPermissions
	 *
	 * @return Completable
	 */
	private function triggerPositionUpdate(?int $position, ?int $parentId = -1, bool $syncPermissions = null): Completable {
		return RestAPI::getInstance()->setChannelPosition($this->getGuildId(), $this->getId(), $position, $parentId, $syncPermissions);
	}
	
	/**
	 * @return Completable<GuildChannel>
	 */
	public function triggerUpdate(): Completable {
		return RestAPI::getInstance()->updateChannel($this->getId(), $this->jsonSerialize());
	}
	
	/**
	 * @return Completable<GuildChannel>
	 */
	protected function internalFetch(): Completable {
		return RestAPI::getInstance()->getChannel($this->getId())->then(fn(GuildChannel $channel) => $this->replaceBy($channel));
	}
	
	/**
	 * @return Completable
	 */
	public function fetchInvites(): Completable {
		return RestAPI::getInstance()->getChannelInvites($this->getId());
	}
	
	/**
	 * Needs to be overwritten and filled with properties copied in subclasses
	 * -> if there are other properties existent
	 *
	 * @internal
	 *
	 * @param GuildChannel $channel
	 *
	 * @return void
	 */
	public function replaceBy(GuildChannel $channel): void {
		$this->name = $channel->getName();
		$this->position = $channel->getPosition();
		$this->parentId = $channel->getParentId();
		$this->overwrites = clone $channel->getOverwrites();
	}
	
	/**
	 * @param string|null $reason
	 *
	 * @return Completable
	 */
	public function delete(string $reason = null): Completable {
		return RestAPI::getInstance()->deleteChannel($this->getId(), $reason);
	}
	
	#[Pure] public function jsonSerialize(): array {
		return ([
			'name' => $this->name,
			'permission_overwrites' => $this->overwrites->values()
		] + $this->getSerializationData());
	}
	
	public function createWebhook(string $name, ?ImageData $avatar = null, ?string $reason = null): Completable {
		Utils::validateNickname($name, 80);
		return RestAPI::getInstance()->createWebhook($this->getId(), $name, $avatar, $reason);
	}
	
	public function fetchWebhooks(): Completable {
		return RestAPI::getInstance()->getChannelWebhooks($this->getId());
	}
	
	public function getSerializationData(): array { return []; }
}