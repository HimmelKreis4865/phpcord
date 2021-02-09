<?php

namespace phpcord\guild;

use phpcord\Discord;
use phpcord\event\message\Emoji;
use phpcord\http\RestAPIHandler;
use phpcord\user\User;
use phpcord\utils\MemberInitializer;
use function array_filter;
use function array_map;
use function is_null;
use function json_decode;
use function strval;

class GuildMessage {

	public const TYPE_DEFAULT = 0;
	public const TYPE_RECIPIENT_ADD	= 1;
	public const TYPE_RECIPIENT_REMOVE = 2;
	public const TYPE_CALL = 3;
	public const TYPE_CHANNEL_NAME_CHANGE = 4;
	public const TYPE_CHANNEL_ICON_CHANGE = 5;
	public const TYPE_CHANNEL_PINNED_MESSAGE = 6;
	public const TYPE_GUILD_MEMBER_JOIN = 7;
	public const TYPE_USER_PREMIUM_GUILD_SUBSCRIPTION = 8;
	public const TYPE_USER_PREMIUM_GUILD_SUBSCRIPTION_TIER_1 = 9;
	public const TYPE_USER_PREMIUM_GUILD_SUBSCRIPTION_TIER_2 = 10;
	public const TYPE_USER_PREMIUM_GUILD_SUBSCRIPTION_TIER_3 = 11;
	public const TYPE_CHANNEL_FOLLOW_ADD = 12;
	public const TYPE_GUILD_DISCOVERY_DISQUALIFIED = 14;
	public const TYPE_GUILD_DISCOVERY_REQUALIFIED = 15;
	public const TYPE_REPLY = 19;
	public const TYPE_APPLICATION_COMMAND = 20;
	
	public $id;

	public $channelId;

	public $guildId;

	public $content;

	public $member;

	public $pinned = false;

	public $type = 0;

	public $tts = false;

	public $timestamp;

	public $referenced_message = null;

	public $mentions = [];

	public $mention_roles = [];

	public $mention_everyone;

	public $edited_timestamp = null;

	public $flags = 0;

	public $embed = null;

	public $reactions = [];
	
	public $attachments = [];

	public function __construct(string $guildId, string $id, string $channelId, string $content, ?GuildMember $member, ?GuildReceivedEmbed $embed = null, string $timestamp = "", bool $tts = false, bool $pinned = false, ?array $referenced_message = null, array $attachments = [], ?string $edited_timestamp = null, int $type = 0, int $flags = 0, bool $mention_everyone = false, array $mentions = [], array $mention_roles = [], array $reactions = []) {
		$this->guildId = $guildId;
		$this->id = $id;
		$this->channelId = $channelId;
		$this->content = $content;
		$this->member = $member;
		$this->embed = $embed;
		$this->pinned = $pinned;
		$this->type = $type;
		$this->tts = $tts;
		$this->timestamp = $timestamp;
		$this->referenced_message = $referenced_message;
		$this->mentions = $mentions;
		$this->mention_everyone = $mention_everyone;
		$this->mention_roles = $mention_roles;
		$this->edited_timestamp = $edited_timestamp;
		$this->flags = $flags;
		$this->attachments = $attachments;
		$this->reactions = array_filter(array_map(function($key) {
			if (is_array($key) and isset($key["name"])) return new Emoji($key["name"], @$key["id"]);
			return null;
		}, $reactions), function($key) {
			return !is_null($key);
		});
	}

	public function hasEmbed(): bool {
		return ($this->embed !== null);
	}

	/**
	 * @return GuildMember
	 */
	public function getMember(): GuildMember {
		return $this->member;
	}

	/**
	 * @return string
	 */
	public function getContent(): string {
		return $this->content;
	}

	/**
	 * @return string
	 */
	public function getId(): string {
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getGuildId(): string {
		return $this->guildId;
	}
	
	public function getGuild(): ?Guild {
		return Discord::getInstance()->getClient()->getGuild($this->getGuildId());
	}

	/**
	 * @return string
	 */
	public function getChannelId(): string {
		return $this->channelId;
	}

	/**
	 * @return array
	 */
	public function getAttachments(): array {
		return $this->attachments;
	}

	/**
	 * @return string|null
	 */
	public function getEditedTimestamp(): ?string {
		return $this->edited_timestamp;
	}

	/**
	 * @return GuildReceivedEmbed|null
	 */
	public function getEmbed(): ?GuildReceivedEmbed {
		return $this->embed;
	}

	/**
	 * @return int
	 */
	public function getFlags(): int {
		return $this->flags;
	}

	/**
	 * @return array
	 */
	public function getMentionRoles(): array {
		return $this->mention_roles;
	}

	/**
	 * @return array
	 */
	public function getMentions(): array {
		return $this->mentions;
	}

	/**
	 * @return array|null
	 */
	public function getReferencedMessage(): ?array {
		return $this->referenced_message;
	}

	/**
	 * @return string
	 */
	public function getTimestamp(): string {
		return $this->timestamp;
	}

	/**
	 * @return int
	 */
	public function getType(): int {
		return $this->type;
	}
	
	public function getReactions(): array {
		return $this->reactions;
	}

	public function reply(string $message): bool {
		return !(RestAPIHandler::getInstance()->sendReply(["channel_id" => (int) $this->channelId, "message_id" => (int) $this->id, "guild_id" => (int) $this->guildId], ["content" => $message]))->isFailed();
	}
	
	public function removeAllReactions(): bool {
		return !(RestAPIHandler::getInstance()->removeAllReactions($this->getChannelId(), $this->getId())->isFailed());
	}
	public function removeMyReaction(Emoji $emoji): bool {
		return !(RestAPIHandler::getInstance()->removeMyReaction($this->getChannelId(), $this->getId(), $emoji)->isFailed());
	}
	
	public function removeReaction(Emoji $emoji): bool {
		return !(RestAPIHandler::getInstance()->removeReactionId($this->getChannelId(), $this->getId(), $emoji)->isFailed());
	}
	
	public function removeUserReaction($user, Emoji $emoji): bool {
		if ($user instanceof User) $user = $user->getId();
		return !(RestAPIHandler::getInstance()->removeUserReaction($this->getChannelId(), $this->getId(), $user, $emoji))->isFailed();
	}
	
	public function getReactionsByEmoji(Emoji $emoji): array {
		$result = RestAPIHandler::getInstance()->getReactions($this->getChannelId(), $this->getId(), $emoji);
		if ($result->isFailed()) return [];
		$guildId = $this->getGuildId();
		if (!is_array(($value = @json_decode($result->getRawData(), true)))) return [];
		return array_map(function($key) use ($guildId) {
			return MemberInitializer::createUser($key, $guildId);
		}, array_filter($value, function($key) {
			return (isset($key["id"]) and isset($key["username"]) and isset($key["avatar"]) and isset($key["discriminator"]));
		}));
	}
	
	public function react(Emoji $emoji): bool {
		return !(RestAPIHandler::getInstance()->createReaction($this->getChannelId(), $this->getId(), $emoji)->isFailed());
	}
	
	public function delete(): bool {
		return !RestAPIHandler::getInstance()->deleteMessage($this->getId(), $this->getChannelId())->isFailed();
	}
	
	public function __toString(): string {
		return $this->getContent();
	}
}


