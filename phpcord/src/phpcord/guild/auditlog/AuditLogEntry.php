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

use phpcord\utils\Collection;
use function array_map;

class AuditLogEntry {
	
	/**
	 * @var Collection $optionalInfo
	 * @phpstan-var Collection<mixed>
	 */
	private Collection $optionalInfo;
	
	/**
	 * @param int $id
	 * @param int $type
	 * @param int|null $userId
	 * @param int|null $targetId
	 * @param AuditLogChange[] $changes
	 * @param array $options
	 * @param string|null $reason
	 */
	public function __construct(private int $id, private int $type, private ?int $userId, private ?int $targetId, private array $changes, array $options = [], private ?string $reason = null) {
		$this->optionalInfo = new Collection($options);
	}
	
	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
	}
	
	/**
	 * One of @link AuditLogTypes
	 *
	 * @return int
	 */
	public function getType(): int {
		return $this->type;
	}
	
	/**
	 * @return int|null
	 */
	public function getUserId(): ?int {
		return $this->userId;
	}
	
	/**
	 * @return int|null
	 */
	public function getTargetId(): ?int {
		return $this->targetId;
	}
	
	/**
	 * @return array
	 */
	public function getChanges(): array {
		return $this->changes;
	}
	
	/**
	 * @return Collection
	 * @phpstan-return Collection<mixed>
	 */
	public function getOptionalInfo(): Collection {
		return $this->optionalInfo;
	}
	
	/**
	 * @return string|null
	 */
	public function getReason(): ?string {
		return $this->reason;
	}
	
	public static function fromArray(array $array): AuditLogEntry {
		return new AuditLogEntry($array['id'], $array['action_type'], @$array['user_id'], @$array['target_id'], array_map(fn(array $data) => AuditLogChange::fromArray($data), $array['changes'] ?? []), $array['options'] ?? [], @$array['reason']);
	}
}