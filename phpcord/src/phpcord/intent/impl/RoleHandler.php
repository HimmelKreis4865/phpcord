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

use phpcord\Discord;
use phpcord\event\role\RoleDeleteEvent;
use phpcord\event\role\RoleUpdateEvent;
use phpcord\event\role\RoleCreateEvent;
use phpcord\guild\permissible\Role;
use phpcord\intent\IntentHandler;
use phpcord\intent\Intents;
use phpcord\runtime\network\packet\IntentMessageBuffer;
use phpcord\utils\Utils;

class RoleHandler implements IntentHandler {
	
	public function handle(IntentMessageBuffer $buffer): void {
		switch ($buffer->name()) {
			case Intents::GUILD_ROLE_CREATE():
				if (!($role = Role::fromArray(($buffer->data()['role'] + $buffer->data())))) return;
				$role->getGuild()->getRoles()->set($role->getId(), $role);
				(new RoleCreateEvent($role))->call();
				break;
			
			case Intents::GUILD_ROLE_UPDATE():
				if (!($role = Role::fromArray($buffer->data()))) return;
				($r = @clone $role->getGuild()->getRoles()->get($role->getId()))?->replaceBy($role);
				if (!$r) $role->getGuild()->getRoles()->set($role->getId(), $role);
				(new RoleUpdateEvent($role, $r))->call();
				break;
			
			case Intents::GUILD_ROLE_DELETE():
				if (!Utils::contains($buffer->data(), 'guild_id', 'role_id')) return;
				(new RoleDeleteEvent(Discord::getInstance()->getClient()->getGuilds()->get(($guildId = $buffer->data()['guild_id']))?->getRoles()->get($buffer->data()['role_id']), $buffer->data()['role_id'], $guildId))->call();
				break;
		}
	}
}