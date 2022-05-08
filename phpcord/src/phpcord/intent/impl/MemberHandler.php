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

namespace phpcord\intent\impl;

use phpcord\event\member\MemberAddEvent;
use phpcord\event\member\MemberRemoveEvent;
use phpcord\event\member\MemberUpdateEvent;
use phpcord\guild\GuildMember;
use phpcord\intent\IntentHandler;
use phpcord\intent\Intents;
use phpcord\runtime\network\packet\IntentMessageBuffer;
use function var_dump;

class MemberHandler implements IntentHandler {
	
	public function handle(IntentMessageBuffer $buffer): void {
		switch ($buffer->name()) {
			case Intents::GUILD_MEMBER_ADD():
				(new MemberAddEvent($member = GuildMember::fromArray($buffer->data())))->call();
				$member->getGuild()->getMembers()->set($member->getId(), $member);
				break;
				
			case Intents::GUILD_MEMBER_UPDATE():
				$member = GuildMember::fromArray($buffer->data());
				if (!$member->getGuild()) return; // this is an unexpected case
				(new MemberUpdateEvent($member->getGuild()->getMembers()->get($member->getId()), $member))->call();
				$member->getGuild()->getMembers()->set($member->getId(), $member);
				break;
			
			case Intents::GUILD_MEMBER_REMOVE():
				(new MemberRemoveEvent($member = GuildMember::fromArray($buffer->data())))->call();
				$member->getGuild()->getMembers()->set($member->getId(), $member);
				break;
		}
	}
}