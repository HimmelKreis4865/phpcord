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

namespace phpcord\channel\types\guild\thread;

use BadMethodCallException;
use phpcord\channel\GuildChannel;
use phpcord\channel\helper\TextChannelBaseTrait;
use phpcord\channel\TextChannel;
use phpcord\utils\Collection;
use phpcord\utils\Utils;

abstract class Thread extends GuildChannel implements TextChannel {
	use TextChannelBaseTrait;
	
	/**
	 * @param int $id
	 * @param int $guildId
	 * @param string $name
	 * @param int $parentId
	 * @param int|null $lastMessageId
	 * @param int $rateLimitPerUser
	 * @param int $ownerId
	 * @param int $messageCount
	 * @param int $memberCount
	 * @param ThreadMetadata $metadata
	 * @param ThreadMember|null $currentBotMember
	 */
	public function __construct(int $id, int $guildId, string $name, int $parentId, private ?int $lastMessageId, private int $rateLimitPerUser, private int $ownerId, private int $messageCount, private int $memberCount, private ThreadMetadata $metadata, private ?ThreadMember $currentBotMember) {
		parent::__construct($id, $guildId, $name, 0, $parentId, []);
	}
	
	public function getPosition(): int {
		throw new BadMethodCallException(static::class . '->getPosition() is no valid function you should be using');
	}
	
	public function getOverwrites(): Collection {
		throw new BadMethodCallException(static::class . '->getOverwrites() is no valid function you should be using');
	}
	
	/**
	 * @return int
	 */
	public function getOwnerId(): int {
		return $this->ownerId;
	}
	
	/**
	 * @return ThreadMetadata
	 */
	public function getMetadata(): ThreadMetadata {
		return $this->metadata;
	}
	
	public function getLastMessageId(): ?int {
		return $this->lastMessageId;
	}
	
	/**
	 * @return int
	 */
	public function getRateLimitPerUser(): int {
		return $this->rateLimitPerUser;
	}
	
	/**
	 * A number between 0-50, 50 will be the maximum displayed since this is made for UI
	 *
	 * @return int
	 */
	public function getMessageCount(): int {
		return $this->messageCount;
	}
	
	/**
	 * A number between 0-50, 50 will be the maximum displayed since this is made for UI
	 *
	 * @return int
	 */
	public function getMemberCount(): int {
		return $this->memberCount;
	}
	
	/**
	 * Returns the member state of the bot user in the current thread
	 * Only existent if the bot is a member of the thread, null otherwise
	 *
	 * @return ThreadMember|null
	 */
	public function getCurrentBotMember(): ?ThreadMember {
		return $this->currentBotMember;
	}
	
	public function onMessageDelete(int $id): void {
		if ($id === $this->lastMessageId) $this->fetch();
	}
	
	public function replaceBy(GuildChannel $channel): void {
		if (!$channel instanceof Thread) return;
		parent::replaceBy($channel);
		$this->lastMessageId = $channel->getLastMessageId();
		$this->rateLimitPerUser = $channel->getRateLimitPerUser();
		$this->ownerId = $channel->getOwnerId();
		$this->metadata = $channel->getMetadata();
		$this->currentBotMember = $channel->getCurrentBotMember();
		$this->memberCount = $channel->getMemberCount();
		$this->messageCount = $channel->getMessageCount();
	}
	
	public static function fromArray(array $array): ?self {
		if (!Utils::contains($array, 'id', 'guild_id', 'name', 'parent_id', 'owner_id', 'thread_metadata')) return null;
		return new static($array['id'], $array['guild_id'], $array['name'], $array['parent_id'], @$array['last_message_id'], $array['rate_limit_per_user'] ?? 0, $array['owner_id'], $array['message_count'] ?? -1, $array['member_count'] ?? -1, ThreadMetadata::fromArray($array['thread_metadata']), (@$array['member'] ? ThreadMember::fromArray($array['member']) : null));
	}
}