<?php

namespace phpcord\utils;

use phpcord\guild\AuditLog;
use phpcord\guild\AuditLogEntry;

class AuditLogInitializer {
	/**
	 * Tries to create an Auditlog instance
	 *
	 * @internal
	 *
	 * @param string $guildId
	 * @param array $data
	 *
	 * @return AuditLog
	 */
	public static function create(string $guildId, array $data): AuditLog {
		$auditLog = new AuditLog($guildId);
		foreach ($data["audit_log_entries"] ?? [] as $entry) {
			$auditLog->addEntry(self::initEntry($entry));
		}

		return $auditLog;
	}
	/**
	 * Tries to create an AuditlogEntry instance used for AuditLogs
	 *
	 * @internal
	 *
	 * @param array $data
	 *
	 * @return AuditLogEntry
	 */
	public static function initEntry(array $data): AuditLogEntry {
		return new AuditLogEntry($data["action_type"], strval($data["id"]), $data);
	}
}