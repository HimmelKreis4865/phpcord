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

namespace phpcord\message;

use BadMethodCallException;
use JetBrains\PhpStorm\Pure;
use phpcord\async\completable\Completable;
use phpcord\channel\TextChannel;
use phpcord\Discord;
use phpcord\exception\IndexNotFoundException;
use phpcord\guild\Guild;
use phpcord\guild\GuildMember;
use phpcord\http\RestAPI;
use phpcord\user\User;
use phpcord\utils\Collection;
use phpcord\utils\Factory;
use phpcord\utils\Timestamp;
use phpcord\utils\Utils;
use function method_exists;
use function var_dump;

class Message {
	
	/**
	 * @var Collection $attachments
	 * @phpstan-var Collection<MessageAttachment>
	 */
	private Collection $attachments;
	
	/**
	 * @param int $id
	 * @param int $channelId
	 * @param User $author
	 * @param string $content
	 * @param int|null $guildId
	 * @param GuildMember|null $member
	 * @param bool $tts
	 * @param Timestamp $createTimestamp
	 * @param Timestamp|null $editTimestamp
	 * @param Reaction[] $reactions
	 * @param MessageAttachment[] $attachments
	 * @param int $flags
	 */
	public function __construct(private int $id, private int $channelId, private User $author, private string $content, private ?int $guildId, private ?GuildMember $member, private bool $tts, private Timestamp $createTimestamp, private ?Timestamp $editTimestamp, private array $reactions, array $attachments = [], private int $flags = 0) {
		$this->attachments = new Collection($attachments);
	}
	
	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
	}
	
	/**
	 * @return int
	 */
	public function getChannelId(): int {
		return $this->channelId;
	}
	
	/**
	 * @return Completable<TextChannel>
	 */
	public function getChannel(): Completable {
		if ($this->guildId)
			return (Discord::getInstance()->getClient()->getGuilds()->get($this->guildId)?->getChannel($this->channelId) ?? Completable::fail(new IndexNotFoundException('Guild ' . $this->guildId . ' does not exist!')));
		return Discord::getInstance()->getClient()->getChannel($this->channelId);
	}
	
	/**
	 * @return int
	 */
	public function getFlags(): int {
		return $this->flags;
	}
	
	public function hasFlag(int $flag): bool {
		return (($this->flags & $flag) === $flag);
	}
	
	public function isGuild(): bool {
		return $this->guildId;
	}
	
	/**
	 * @return User
	 */
	public function getAuthor(): User {
		return $this->author;
	}
	
	/**
	 * @return bool
	 */
	public function isTts(): bool {
		return $this->tts;
	}
	
	/**
	 * @return int|null
	 */
	public function getGuildId(): ?int {
		return $this->guildId;
	}
	
	public function getGuild(): ?Guild {
		return Discord::getInstance()->getClient()->getGuilds()->get($this->getGuildId());
	}
	
	/**
	 * @return GuildMember|null
	 */
	public function getMember(): ?GuildMember {
		return $this->member;
	}
	
	/**
	 * @return Collection
	 */
	public function getAttachments(): Collection {
		return $this->attachments;
	}
	
	/**
	 * @return string
	 */
	public function getContent(): string {
		return $this->content;
	}
	
	/**
	 * @return Timestamp
	 */
	public function getCreateTimestamp(): Timestamp {
		return $this->createTimestamp;
	}
	
	/**
	 * @return Timestamp|null
	 */
	public function getEditTimestamp(): ?Timestamp {
		return $this->editTimestamp;
	}
	
	/**
	 * @param string|null $reason
	 *
	 * @return Completable
	 */
	public function delete(string $reason = null): Completable {
		return RestAPI::getInstance()->deleteMessage($this->getChannelId(), $this->getId(), $reason);
	}
	
	/**
	 * @return MessageReference
	 */
	#[Pure] public function asReference(): MessageReference {
		return MessageReference::fromMessage($this);
	}
	
	/**
	 * @return Reaction[]
	 */
	public function getReactions(): array {
		return $this->reactions;
	}
	
	/**
	 * @param PartialEmoji $emoji
	 *
	 * @return Completable
	 */
	public function react(PartialEmoji $emoji): Completable {
		if ($this->hasFlag(MessageFlags::EPHEMERAL())) return Completable::fail(new BadMethodCallException('Cannot react to an ephemeral message'));
		return RestAPI::getInstance()->reactMessage($this->getChannelId(), $this->getId(), $emoji);
	}
	
	/**
	 * @fixme the resulting array was always empty during tests
	 *
	 * @param PartialEmoji $emoji
	 *
	 * @return Completable<array<User>>
	 */
	public function fetchReactionUsers(PartialEmoji $emoji): Completable {
		if ($this->hasFlag(MessageFlags::EPHEMERAL())) return Completable::fail(new BadMethodCallException('Cannot react to an ephemeral message'));
		return RestAPI::getInstance()->getReactions($this->getChannelId(), $this->getId(), $emoji);
	}
	
	/**
	 * @param PartialEmoji $emoji
	 * @param int|null $userId if null the bot is used as target user
	 *
	 * @return Completable
	 */
	public function deleteReaction(PartialEmoji $emoji, ?int $userId = null): Completable {
		if ($this->hasFlag(MessageFlags::EPHEMERAL())) return Completable::fail(new BadMethodCallException('Cannot react to an ephemeral message'));
		return RestAPI::getInstance()->deleteReaction($this->getChannelId(), $this->getId(), $emoji, $userId);
	}
	
	/**
	 * @param PartialEmoji|null $emoji if not set, all reactions of the message will be deleted
	 *
	 * @return Completable
	 */
	public function deleteReactions(?PartialEmoji $emoji = null): Completable {
		if ($this->hasFlag(MessageFlags::EPHEMERAL())) return Completable::fail(new BadMethodCallException('Cannot react to an ephemeral message'));
		return ($emoji ? RestAPI::getInstance()->deleteAllEmojiReactions($this->getChannelId(), $this->getId(), $emoji) : RestAPI::getInstance()->deleteAllReactions($this->getChannelId(), $this->getId()));
	}
	
	/**
	 * @param Sendable $sendable
	 *
	 * @return Completable<Message>
	 */
	public function reply(Sendable $sendable): Completable {
		if (!method_exists($sendable, 'setReference')) throw new BadMethodCallException('Cannot set the reference of a sendable of class ' . $sendable::class);
		$sendable->setReference($this->asReference());
		return RestAPI::getInstance()->sendMessage($this->channelId, $sendable);
	}
	
	public static function fromArray(array $array): ?Message {
		if (!Utils::contains($array, 'id', 'author', 'timestamp', 'channel_id', 'content')) return null;
		return new Message($array['id'], $array['channel_id'], User::fromArray($array['author']), $array['content'], @$array['guild_id'], (@$array['member'] ? GuildMember::fromArray(($array['member'] + ['guild_id' => @$array['guild_id'], 'user' => $array['author']])) : null), $array['tts'] ?? false, Timestamp::fromDate($array['timestamp']), (@$array['edited_timestamp'] ? Timestamp::fromDate($array['edited_timestamp']) : null), Factory::createReactionArray($array['reactions'] ?? []), Factory::createMessageAttachments($array['attachments'] ?? []));
	}
}