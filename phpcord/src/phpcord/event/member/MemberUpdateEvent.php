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

namespace phpcord\event\member;

use JetBrains\PhpStorm\Pure;
use phpcord\guild\GuildMember;

class MemberUpdateEvent extends MemberEvent {
	
	/**
	 * @param GuildMember $oldMember
	 * @param GuildMember $member
	 */
	#[Pure] public function __construct(private GuildMember $oldMember, GuildMember $member) {
		parent::__construct($member);
	}
	
	/**
	 * @return GuildMember
	 */
	public function getOldMember(): GuildMember {
		return $this->oldMember;
	}
}