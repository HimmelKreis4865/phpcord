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

namespace phpcord\guild;

use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;
use JsonSerializable;
use phpcord\async\completable\Completable;
use phpcord\Discord;
use phpcord\guild\permissible\PermissionIds;
use phpcord\guild\permissible\Role;
use phpcord\http\RestAPI;
use phpcord\image\Icon;
use phpcord\utils\CollectionNotifier;
use phpcord\utils\Factory;
use phpcord\utils\Timestamp;
use phpcord\user\User;
use phpcord\utils\Collection;
use phpcord\utils\Utils;
use function array_map;
use function intval;
use function var_dump;

class GuildMember extends User implements JsonSerializable {
	
	/**
	 * The maximum difference between now and target timeout duration, currently at 28 days
	 */
	private const MAX_TIMEOUT_SPAN = (28 * 24 * 60 * 60);
	
	/**
	 * @var Collection $roles
	 * @phpstan-var Collection<int>
	 */
	private Collection $roles;
	
	/**
	 * @param int $guildId
	 * @param int $id
	 * @param string $username
	 * @param string $discriminator
	 * @param Icon|null $avatar
	 * @param int $flags
	 * @param bool $bot
	 * @param string|null $email
	 * @param bool $verified
	 * @param int[] $roles
	 * @param Timestamp|null $joinedAt
	 * @param Timestamp|null $timeoutUntil
	 * @param string|null $nick
	 * @param bool $mute
	 * @param bool $deaf
	 */
	public function __construct(private int $guildId, int $id, string $username, string $discriminator, ?Icon $avatar, int $flags = 0, bool $bot = false, ?string $email = null, bool $verified = true, array $roles = [], private ?Timestamp $joinedAt = null, private ?Timestamp $timeoutUntil = null, private ?string $nick = null, private bool $mute = false, private bool $deaf = false) {
		parent::__construct($id, $username, $discriminator, $avatar, $flags, $bot, $email, $verified);
		$this->roles = new CollectionNotifier($roles);
		$this->roles->registerSetListener(fn() => $this->modify(['roles' => $this->roles->values()]));
		$this->roles->registerRemoveListener(fn() => $this->modify(['roles' => $this->roles->values()]));
	}
	
	/**
	 * @return int
	 */
	public function getGuildId(): int {
		return $this->guildId;
	}
	
	public function getGuild(): ?Guild {
		return Discord::getInstance()->getClient()->getGuilds()->get($this->guildId);
	}
	
	/**
	 * @return Timestamp|null
	 */
	public function getJoinedAt(): ?Timestamp {
		return $this->joinedAt;
	}
	
	/**
	 * @return Timestamp|null
	 */
	public function getTimeoutUntil(): ?Timestamp {
		return $this->timeoutUntil;
	}
	
	public function hasTimeout(): bool {
		return !($this->timeoutUntil?->inPast() ?? true);
	}
	
	/**
	 * @param Timestamp|null $timestamp Set to null to remove the timeout
	 * @param string|null $reason
	 *
	 * @return void
	 */
	public function setTimeoutUntil(?Timestamp $timestamp, ?string $reason = null): void {
		if ($timestamp and ($timestamp->inPast() or $timestamp->diff(Timestamp::now()) > self::MAX_TIMEOUT_SPAN)) throw new InvalidArgumentException('The timestamp to set the timeout to must not exceed a 28 days limit in future');
		$this->timeoutUntil = $timestamp;
		$this->modify([
			'communication_disabled_until' => $this->timeoutUntil
		], $reason);
	}
	
	/**
	 * @return bool
	 */
	public function isMuted(): bool {
		return $this->mute;
	}
	
	/**
	 * @return bool
	 */
	public function isDeaf(): bool {
		return $this->deaf;
	}
	
	/**
	 * @return Collection<int>
	 */
	public function getRoles(): Collection {
		return $this->roles;
	}
	
	public function addRole(int|Role $role): void {
		$this->roles->set(($id = ($role instanceof Role ? $role->getId() : $role)), $id);
	}
	
	public function removeRole(int|Role $role): void {
		$this->roles->unset(($role instanceof Role ? $role->getId() : $role));
	}
	
	/**
	 * @return string|null
	 */
	public function getNick(): ?string {
		return $this->nick;
	}
	
	#[Pure] public function getCreationTimestamp(): Timestamp {
		return Timestamp::fromSnowflake($this->getId());
	}
	
	/**
	 * @param string|null $reason
	 * @param int $deleteMessageInDays
	 *
	 * @return Completable
	 */
	public function ban(string $reason = null, int $deleteMessageInDays = 0): Completable {
		return RestAPI::getInstance()->createBan($this->getGuildId(), $this->getId(), $reason, $deleteMessageInDays);
	}
	
	/**
	 * @param string|null $reason
	 *
	 * @return Completable
	 */
	public function kick(string $reason = null): Completable {
		return RestAPI::getInstance()->removeMember($this->getGuildId(), $this->getId(), $reason);
	}
	
	/**
	 * @param array $values
	 * @param string|null $reason
	 *
	 * @return Completable
	 */
	private function modify(array $values, string $reason = null): Completable {
		return RestAPI::getInstance()->updateMember($this->getGuildId(), $this->getId(), $values, $reason);
	}
	
	/**
	 * @param string|null $nick
	 * @param string|null $reason
	 *
	 * @return Completable
	 */
	public function setNick(?string $nick, string $reason = null): Completable {
		return $this->modify(['nick' => $nick], $reason);
	}
	
	/**
	 * Mutes a member in voice chats
	 * Fails if the player is currently not connected
	 *
	 * @param bool $mute
	 * @param string|null $reason
	 *
	 * @return Completable
	 */
	public function mute(bool $mute = true, string $reason = null): Completable {
		return $this->modify(['mute' => $mute], $reason);
	}
	
	/**
	 * Deafens a member in voice chats
	 * Fails if the player is currently not connected
	 *
	 * @param bool $deaf
	 * @param string|null $reason
	 *
	 * @return Completable
	 */
	public function deaf(bool $deaf = true, string $reason = null): Completable {
		return $this->modify(['deaf' => $deaf], $reason);
	}
	
	/**
	 * Moves the member to another channel
	 *
	 * @param int|null $channelId
	 * @param string|null $reason
	 *
	 * @return Completable
	 */
	public function setChannel(?int $channelId, ?string $reason = null): Completable {
		return $this->modify(['channel_id' => $channelId], $reason);
	}
	
	/**
	 * Returns whether the member has a permission or not
	 * Uses hasPermission of the roles
	 *
	 * @param int $permission
	 * @see PermissionIds
	 *
	 * @return bool
	 */
	public function hasPermission(int $permission): bool {
		if ($this->getGuild()->isOwner($this)) return true;
		foreach ($this->getRoles() as $role)
			if ($this->getGuild()->getRoles()->get($role)->hasPermission($permission)) return true;
		return false;
	}
	
	public static function fromArray(array $array): ?GuildMember {
		$user = User::fromArray($array['user'] ?? []) ?? User::fromArray($array);
		if (!$user or !Utils::contains($array, 'guild_id')) return null;
		return new GuildMember($array['guild_id'], $user->getId(), $user->getName(), $user->getDiscriminator(), $user->getAvatar(), $user->getFlags(), $user->isBot(), $user->getEmail(), $user->isVerified(), Factory::createRoleIdArray($array['roles'] ?? []), (@$array['joined_at'] ? Timestamp::fromDate($array['joined_at']) : null), (@$array['communication_disabled_until'] ? Timestamp::fromDate($array['communication_disabled_until']) : null), @$array['nick'], $array['mute'] ?? false, $array['deaf'] ?? false);
	}
}