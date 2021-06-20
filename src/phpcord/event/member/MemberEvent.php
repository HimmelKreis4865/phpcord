<?php

namespace phpcord\event\member;

use phpcord\Discord;
use phpcord\event\Event;
use phpcord\guild\Guild;
use phpcord\guild\GuildMember;

class MemberEvent extends Event {
	/** @var GuildMember $member */
	protected $member;
	
	/**
	 * MemberEvent constructor.
	 *
	 * @param GuildMember $member
	 */
	public function __construct(GuildMember $member) {
		$this->member = $member;
	}

	/**
	 * Returns the member instance of the GuildMember used
	 *
	 * @api
	 *
	 * @return GuildMember
	 */
	public function getMember(): GuildMember {
		return $this->member;
	}
	
	/**
	 * Returns the guild id the player was added to
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getGuildId(): string {
		return $this->getMember()->guild_id;
	}
	
	/**
	 * Returns the guild the player was added to
	 *
	 * @api
	 *
	 * @return Guild|null
	 */
	public function getGuild(): ?Guild {
		return Discord::getInstance()->getClient()->getGuild($this->getGuildId());
	}
}