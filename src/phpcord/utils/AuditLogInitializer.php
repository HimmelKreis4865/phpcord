<?php

namespace phpcord\utils;

use phpcord\guild\AuditLog;
use phpcord\guild\AuditLogEntry;

class AuditLogInitializer {
	public static function create(string $guildId, array $data): AuditLog {
		var_dump("initialising...");
		$auditLog = new AuditLog($guildId);
		foreach ($data["audit_log_entries"] ?? [] as $entry) {
			$auditLog->addEntry(self::initEntry($entry));
		}

		return $auditLog;
	}

	public static function initEntry(array $data): AuditLogEntry {
		return new AuditLogEntry($data["action_type"], strval($data["id"]), $data);
	}
}


