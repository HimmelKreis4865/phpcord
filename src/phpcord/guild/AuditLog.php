<?php

namespace phpcord\guild;

class AuditLog {
	/** @var array $entries */
	protected $entries = [];
	
	/** @var string $guildId */
	protected $guildId;

	/**
	 * AuditLog constructor.
	 *
	 * @param string $guildId
	 */
	public function __construct(string $guildId) {
		$this->guildId = $guildId;
	}

	/**
	 * Adds an entry to the auditlog, note: will not affect the real audit log!
	 *
	 * @internal
	 *
	 * @param AuditLogEntry $entry
	 */
	public function addEntry(AuditLogEntry $entry) {
		$this->entries[$entry->getId()] = $entry;
	}

	/**
	 * Returns a specific entry by ID
	 *
	 * @api
	 *
	 * @param string $id
	 *
	 * @return AuditLogEntry|null
	 */
	public function getEntry(string $id): ?AuditLogEntry {
		return @$this->entries[$id];
	}

	/**
	 * Returns a list of all Entries
	 *
	 * @api
	 *
	 * @return array
	 */
	public function getEntries(): array {
		return $this->entries;
	}
}