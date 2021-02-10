<?php

namespace phpcord\event\member;

use phpcord\Discord;
use phpcord\guild\Emoji;
use phpcord\guild\GuildMember;
use phpcord\guild\GuildMessage;

class ReactionAddEvent extends MemberEvent {
	/** @var string $channel_id */
	public $channel_id;
	
	/** @var string $message_id */
	public $message_id;
	
	/** @var Emoji $emoji */
	public $emoji;

	/**
	 * ReactionRemoveEvent constructor.
	 *
	 * @param GuildMember $member
	 * @param string $message_id
	 * @param string $channel_id
	 * @param Emoji $emoji
	 */
	public function __construct(GuildMember $member, string $message_id, string $channel_id, Emoji $emoji) {
		parent::__construct($member);
		$this->message_id = $message_id;
		$this->channel_id = $channel_id;
		$this->emoji = $emoji;
	}
	
	/**
	 * Tries to fetch the message of the event
	 *
	 * @api
	 *
	 * @return GuildMessage|null
	 */
	public function getMessage(): ?GuildMessage {
		return Discord::getInstance()->getClient()->getGuild($this->getMember()->getGuildId())->getChannel($this->channel_id)->getMessage($this->message_id);
	}
}