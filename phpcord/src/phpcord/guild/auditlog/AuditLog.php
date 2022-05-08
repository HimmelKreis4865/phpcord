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

namespace phpcord\guild\auditlog;

use phpcord\user\User;
use phpcord\utils\Collection;
use phpcord\utils\Factory;

class AuditLog {
	
	/**
	 * @var Collection $entries
	 * @phpstan-var Collection<AuditLogEntry>
	 */
	private Collection $entries;
	
	/**
	 * @var Collection $users
	 * @phpstan-var Collection<User>
	 */
	private Collection $users;
	
	/**
	 * @param AuditLogEntry[] $entries
	 * @param User[] $users
	 * @param array $guildScheduledEvents
	 * @param array $webhooks
	 */
	public function __construct(array $entries, array $users, array $guildScheduledEvents = [], array $webhooks = []) {
		$this->entries = new Collection($entries);
		$this->users = new Collection($users);
	}
	
	/**
	 * @return Collection<AuditLogEntry>
	 */
	public function getEntries(): Collection {
		return $this->entries;
	}
	
	/**
	 * @return Collection<User>
	 */
	public function getUsers(): Collection {
		return $this->users;
	}
	
	public static function fromArray(array $array): AuditLog {
		return new AuditLog(Factory::createAuditLogEntryArray($array['audit_log_entries']), Factory::createUserArray($array['users']));
	}
}