<?php

namespace phpcord\guild;

use InvalidArgumentException;
use phpcord\channel\embed\MessageEmbed;
use phpcord\channel\NewsChannel;
use phpcord\channel\Sendable;
use phpcord\channel\TextMessage;
use phpcord\Discord;
use phpcord\guild\store\GuildStoredMessage;
use phpcord\http\RestAPIHandler;
use phpcord\user\User;
use phpcord\utils\ArrayUtils;
use phpcord\utils\MemberInitializer;
use function array_filter;
use function array_map;
use function is_null;
use function is_string;
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
	
	/** @var string $id */
	public $id;
	
	/** @var string $channelId */
	public $channelId;
	
	/** @var string $guildId */
	public $guildId;

	/** @var string $content */
	public $content;

	/** @var GuildMember|null $member */
	public $member;

	/** @var bool $pinned */
	public $pinned = false;

	/** @var int $type */
	public $type = 0;

	/** @var bool $tts */
	public $tts = false;

	/** @var string $timestamp */
	public $timestamp;
	
	/** @var GuildStoredMessage|null $referenced_message */
	public $referenced_message = null;

	/** @var array $mentions */
	public $mentions = [];

	/** @var array $mention_roles */
	public $mention_roles = [];

	/** @var bool $mention_everyone */
	public $mention_everyone;

	/** @var string|null $edited_timestamp */
	public $edited_timestamp = null;

	/** @var int $flags */
	public $flags = 0;
	
	/** @var GuildReceivedEmbed|null $embed */
	public $embed = null;

	/** @var array $reactions */
	public $reactions = [];
	
	/** @var array $attachments */
	public $attachments = [];
	
	/**
	 * GuildMessage constructor.
	 *
	 * @param string $guildId
	 * @param string $id
	 * @param string $channelId
	 * @param string $content
	 * @param GuildMember|null $member
	 * @param GuildReceivedEmbed|null $embed
	 * @param string $timestamp
	 * @param bool $tts
	 * @param bool $pinned
	 * @param GuildStoredMessage|null $referenced_message
	 * @param array $attachments
	 * @param string|null $edited_timestamp
	 * @param int $type
	 * @param int $flags
	 * @param bool $mention_everyone
	 * @param array $mentions
	 * @param array $mention_roles
	 * @param array $reactions
	 */
	public function __construct(string $guildId, string $id, string $channelId, string $content, ?GuildMember $member, ?GuildReceivedEmbed $embed = null, string $timestamp = "", bool $tts = false, bool $pinned = false, ?GuildStoredMessage $referenced_message = null, array $attachments = [], ?string $edited_timestamp = null, int $type = 0, int $flags = 0, bool $mention_everyone = false, array $mentions = [], array $mention_roles = [], array $reactions = []) {
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
	
	/**
	 * Returns whether the message includes an embed or not
	 *
	 * @api
	 *
	 * @return bool
	 */
	public function hasEmbed(): bool {
		return ($this->embed !== null);
	}

	/**
	 * Returns the member who wrote the message
	 *
	 * @api
	 *
	 * @return null|GuildMember
	 */
	public function getMember(): ?GuildMember {
		return $this->member;
	}

	/**
	 * Returns the plain content of the message
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getContent(): string {
		return $this->content;
	}

	/**
	 * Returns the MessageID
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getId(): string {
		return $this->id;
	}

	/**
	 * Returns the GuildID the message was sent in
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getGuildId(): string {
		return $this->guildId;
	}
	
	/**
	 * Tries to get the guild from cache @see getGuildId()
	 *
	 * @api
	 *
	 * @return Guild|null
	 */
	public function getGuild(): ?Guild {
		return Discord::getInstance()->getClient()->getGuild($this->getGuildId());
	}

	/**
	 * Returns the ChannelID the message was sent in
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getChannelId(): string {
		return $this->channelId;
	}

	/**
	 * Returns an array with all attachments
	 *
	 * @warning Not supported yet
	 *
	 * @api
	 *
	 * @return array
	 */
	public function getAttachments(): array {
		return $this->attachments;
	}

	/**
	 * Returns the timestamp of the last edit, null if it wasn't edited yet
	 *
	 * @api
	 *
	 * @return string|null
	 */
	public function getEditedTimestamp(): ?string {
		return $this->edited_timestamp;
	}

	/**
	 * Returns an Embed that was received
	 *
	 * @warning No MessageEmbed instance to remove setters
	 *
	 * @api
	 *
	 * @return GuildReceivedEmbed|null
	 */
	public function getEmbed(): ?GuildReceivedEmbed {
		return $this->embed;
	}

	/**
	 * Returns the flags of the message
	 *
	 * @api
	 *
	 * @return int
	 */
	public function getFlags(): int {
		return $this->flags;
	}

	/**
	 * Returns an array with all mentioned roles
	 *
	 * @api
	 *
	 * @return array
	 */
	public function getMentionRoles(): array {
		return $this->mention_roles;
	}

	/**
	 * Returns an array with all mentions Members
	 *
	 * @api
	 *
	 * @return array
	 */
	public function getMentions(): array {
		return $this->mentions;
	}

	/**
	 * Returns a referenced message (as "reply")
	 *
	 * @api
	 *
	 * @return GuildStoredMessage|null
	 */
	public function getReferencedMessage(): ?GuildStoredMessage {
		return $this->referenced_message;
	}

	/**
	 * Returns the timestamp the message was sent
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getTimestamp(): string {
		return $this->timestamp;
	}

	/**
	 * Returns the type of the message
	 *
	 * @api
	 *
	 * @return int
	 */
	public function getType(): int {
		return $this->type;
	}
	
	/**
	 * Returns an array with all Embeds
	 *
	 * @api
	 *
	 * @return Emoji[]
	 */
	public function getReactions(): array {
		return $this->reactions;
	}
	
	/**
	 * Directly replies to a message
	 *
	 * @api
	 *
	 * @param string|Sendable $message
	 *
	 * @return bool
	 */
	public function reply($message): bool {
		if (is_string($message)) $message = new TextMessage(strval($message));
		if (!$message instanceof Sendable) return false;
		
		return !(RestAPIHandler::getInstance()->sendReply(["channel_id" => (int) $this->channelId, "message_id" => (int) $this->id, "guild_id" => (int) $this->guildId], json_decode($message->getFormattedData(), true)))->isFailed();
	}
	
	/**
	 * Publishes a message to following channels
	 *
	 * @api
	 *
	 * @return bool
	 */
	public function crosspost(): bool {
		$channel = $this->getGuild()->getChannel($this->getChannelId());
		if ($channel !== null and !($channel instanceof NewsChannel))
			throw new InvalidArgumentException("Cannot crosspost a message in a channel that is no news channel!");
		
		return !(RestAPIHandler::getInstance()->crosspostMessage($this->getChannelId(), $this->getId()))->isFailed();
	}
	
	/**
	 * Removes all reactions of a message
	 *
	 * @api
	 *
	 * @return bool
	 */
	public function removeAllReactions(): bool {
		return !(RestAPIHandler::getInstance()->removeAllReactions($this->getChannelId(), $this->getId())->isFailed());
	}
	
	/**
	 * Removes the reactions of the bot
	 *
	 * @api
	 *
	 * @param Emoji $emoji
	 *
	 * @return bool
	 */
	public function removeMyReaction(Emoji $emoji): bool {
		return !(RestAPIHandler::getInstance()->removeMyReaction($this->getChannelId(), $this->getId(), $emoji)->isFailed());
	}
	
	/**
	 * Removes a complete Emoji from the message
	 *
	 * @api
	 *
	 * @param Emoji $emoji
	 *
	 * @return bool
	 */
	public function removeReaction(Emoji $emoji): bool {
		return !(RestAPIHandler::getInstance()->removeReactionId($this->getChannelId(), $this->getId(), $emoji)->isFailed());
	}
	
	/**
	 * Removes the reaction of a specific user from an Emoji
	 *
	 * @api
	 *
	 * @param User|string $user
	 * @param Emoji $emoji
	 *
	 * @return bool
	 */
	public function removeUserReaction($user, Emoji $emoji): bool {
		if ($user instanceof User) $user = $user->getId();
		return !(RestAPIHandler::getInstance()->removeUserReaction($this->getChannelId(), $this->getId(), $user, $emoji))->isFailed();
	}
	
	/**
	 * Returns a User - Array with all reactions of an Emoji
	 *
	 * @api
	 *
	 * @param Emoji $emoji
	 *
	 * @return array
	 */
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
	
	/**
	 * Reacts to the message with an Emoji instance
	 *
	 * @api
	 *
	 * @param Emoji $emoji
	 *
	 * @return bool
	 */
	public function react(Emoji $emoji): bool {
		return !(RestAPIHandler::getInstance()->createReaction($this->getChannelId(), $this->getId(), $emoji)->isFailed());
	}
	
	/**
	 * Tries to delete the message, returns false on failure
	 *
	 * @api
	 *
	 * @return bool
	 */
	public function delete(): bool {
		return !RestAPIHandler::getInstance()->deleteMessage($this->getId(), $this->getChannelId())->isFailed();
	}
	
	/**
	 * Returns the content of the message once it has to be converted to a string
	 *
	 * @api
	 *
	 * @return string
	 */
	public function __toString(): string {
		return $this->getContent();
	}
	
	/**
	 * Pins a message in the channel, returns false on failure
	 *
	 * @api
	 *
	 * @return bool
	 */
	public function pin(): bool {
		if (in_array($this->getType(), [self::TYPE_CALL, self::TYPE_USER_PREMIUM_GUILD_SUBSCRIPTION, self::TYPE_GUILD_MEMBER_JOIN, self::TYPE_CHANNEL_PINNED_MESSAGE, self::TYPE_USER_PREMIUM_GUILD_SUBSCRIPTION, self::TYPE_USER_PREMIUM_GUILD_SUBSCRIPTION_TIER_2, self::TYPE_USER_PREMIUM_GUILD_SUBSCRIPTION_TIER_3, self::TYPE_CHANNEL_FOLLOW_ADD]))
			throw new InvalidArgumentException("Could not pin a message of type " . $this->getType());
		return !RestAPIHandler::getInstance()->pinMessage($this->getChannelId(), $this->getId())->isFailed();
	}
	
	/**
	 * Unpins a message in the channel, returns false on failure
	 *
	 * @api
	 *
	 * @return bool
	 */
	public function unpin(): bool {
		return !RestAPIHandler::getInstance()->unpinMessage($this->getChannelId(), $this->getId())->isFailed();
	}
	
	/**
	 * Edits a message, don't edit messages that are not sent by your bot!
	 *
	 * @api
	 *
	 * @param string|null $content
	 * @param MessageEmbed|null $embed
	 *
	 * @return bool
	 */
	public function edit(?string $content, ?MessageEmbed $embed = null): bool {
		// todo: validate message was sent by the application
		$data = [];
		if ($content !== null) {
			$this->content = $content;
			$data["content"] = $content;
		}
		if ($embed !== null) {
			$data["embed"] = ArrayUtils::filterNullRecursive($embed->data);
		}
		
		return !RestAPIHandler::getInstance()->editMessage($this->getChannelId(), $this->getId(), $data)->isFailed();
	}
}