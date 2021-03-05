<?php

namespace phpcord\guild\store;

use phpcord\guild\GuildMessage;
use phpcord\guild\GuildReceivedEmbed;
use phpcord\user\User;
use phpcord\utils\MessageInitializer;

class GuildStoredMessage extends GuildMessage {
	/** @var User $author */
	protected $author;
	
	/**
	 * GuildStoredMessage constructor.
	 *
	 * @param string $guildId
	 * @param string $id
	 * @param string $channelId
	 * @param string $content
	 * @param User $author
	 * @param GuildReceivedEmbed|null $embed
	 * @param string $timestamp
	 * @param bool $tts
	 * @param bool $pinned
	 * @param array|null $referenced_message
	 * @param array $attachments
	 * @param string|null $edited_timestamp
	 * @param int $type
	 * @param int $flags
	 * @param bool $mention_everyone
	 * @param array $mentions
	 * @param array $mention_roles
	 */
	public function __construct(string $guildId, string $id, string $channelId, string $content, User $author, ?GuildReceivedEmbed $embed = null, string $timestamp = "", bool $tts = false, bool $pinned = false, ?array $referenced_message = null, array $attachments = [], ?string $edited_timestamp = null, int $type = 0, int $flags = 0, bool $mention_everyone = false, array $mentions = [], array $mention_roles = []) {
		$this->author = $author;
		if (is_array($referenced_message)) $referenced_message = MessageInitializer::fromStore($guildId, $referenced_message);
		
		parent::__construct($guildId, $id, $channelId, $content, null, $embed, $timestamp, $tts, $pinned, $referenced_message, $attachments, $edited_timestamp, $type, $flags, $mention_everyone, $mentions, $mention_roles);
	}
	
	/**
	 * Returns the author of the message - member will be null here
	 *
	 * @api
	 *
	 * @return User
	 */
	public function getAuthor(): User {
		return $this->author;
	}
}