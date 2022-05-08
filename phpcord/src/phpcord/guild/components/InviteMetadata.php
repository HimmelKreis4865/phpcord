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

namespace phpcord\guild\components;

use phpcord\utils\Timestamp;
use phpcord\utils\Utils;

class InviteMetadata {
	
	/**
	 * @param int $uses
	 * @param int $maxUses
	 * @param int $maxAge
	 * @param Timestamp $createdAt
	 * @param bool $temporary
	 */
	public function __construct(private int $uses, private int $maxUses, private int $maxAge, private Timestamp $createdAt, private bool $temporary = false) { }
	
	/**
	 * @return int
	 */
	public function getUses(): int {
		return $this->uses;
	}
	
	/**
	 * @return int
	 */
	public function getMaxAge(): int {
		return $this->maxAge;
	}
	
	/**
	 * @return int
	 */
	public function getMaxUses(): int {
		return $this->maxUses;
	}
	
	/**
	 * @return Timestamp
	 */
	public function getCreationTimestamp(): Timestamp {
		return $this->createdAt;
	}
	
	/**
	 * @return bool
	 */
	public function isTemporary(): bool {
		return $this->temporary;
	}
	
	public static function fromArray(array $array): ?InviteMetadata {
		if (!Utils::contains($array['uses'], $array['max_uses'], $array['max_age'], $array['created_at'])) return null;
		return new InviteMetadata($array['uses'], $array['max_uses'], $array['max_age'], Timestamp::fromDate($array['created_at']), $array['temporary'] ?? false);
	}
}