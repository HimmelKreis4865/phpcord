<?php

namespace phpcord\event\member;

use phpcord\event\Event;
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

	public function getGuildId(): string {
		return $this->getMember()->guild_id;
	}
}


