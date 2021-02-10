<?php

namespace phpcord\guild;

class AuditLogEntry implements AuditLogEntryTypes {
	/** @var int $type */
	protected $type;
	
	/** @var string $id */
	protected $id;
	
	/** @var array $data */
	protected $data;

	/**
	 * AuditLogEntry constructor.
	 *
	 * @param int $type
	 * @param string $id
	 * @param array $data
	 */
	public function __construct(int $type, string $id, array $data = []) {
		$this->type = $type;
		$this->id = $id;
		$this->data = $data;
	}


	/**
	 * Returns the ID of the Entry
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getId(): string {
		return $this->id;
	}

	/**
	 * Returns the type of the entry
	 *
	 * @api
	 *
	 * @return int
	 */
	public function getType(): int {
		return $this->type;
	}
	
	/**
	 * Includes all additional data such as targetID, channelID,..
	 *
	 * @api
	 *
	 * @return array
	 */
	public function getData(): array {
		return $this->data;
	}
}