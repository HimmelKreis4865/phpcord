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

use phpcord\event\Event;
use phpcord\guild\GuildMember;

abstract class MemberEvent extends Event {
	
	/**
	 * @param GuildMember $member
	 */
	public function __construct(private GuildMember $member) { }
	
	/**
	 * @return GuildMember
	 */
	public function getMember(): GuildMember {
		return $this->member;
	}
}