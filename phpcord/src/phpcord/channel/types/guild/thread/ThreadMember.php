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

namespace phpcord\channel\types\guild\thread;

use phpcord\utils\Timestamp;
use phpcord\utils\Utils;

class ThreadMember {
	
	/**
	 * @param int $id
	 * @param int $threadId
	 * @param Timestamp $joinTimestamp
	 * @param int $flags
	 */
	public function __construct(private int $id, private int $threadId, private Timestamp $joinTimestamp, private int $flags) { }
	
	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
	}
	
	/**
	 * @return int
	 */
	public function getThreadId(): int {
		return $this->threadId;
	}
	
	/**
	 * @return Timestamp
	 */
	public function getJoinTimestamp(): Timestamp {
		return $this->joinTimestamp;
	}
	
	/**
	 * @return int
	 */
	public function getFlags(): int {
		return $this->flags;
	}
	
	public static function fromArray(array $array): ?ThreadMember {
		if (!Utils::contains($array, 'user_id', 'id', 'flags', 'join_timestamp')) return null;
		return new ThreadMember($array['user_id'], $array['id'], Timestamp::fromDate($array['join_timestamp']), $array['flags']);
	}
}