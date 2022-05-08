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

class ThreadMetadata {
	
	public const VALID_ARCHIVE_DURATIONS = [60, 1440, 4320, 10080];
	
	/**
	 * @param bool $archived
	 * @param int $autoArchiveDuration
	 * @param Timestamp $archiveTimestamp
	 * @param bool $locked
	 * @param bool $invitable
	 * @param Timestamp|null $createTimestamp
	 */
	public function __construct(private bool $archived, private int $autoArchiveDuration, private Timestamp $archiveTimestamp, private bool $locked, private bool $invitable, private ?Timestamp $createTimestamp) { }
	
	/**
	 * @return bool
	 */
	public function isArchived(): bool {
		return $this->archived;
	}
	
	/**
	 * duration in minutes to automatically archive the thread after recent activity, can be @see ThreadMetadata::VALID_ARCHIVE_DURATIONS
	 *
	 * @return int
	 */
	public function getAutoArchiveDuration(): int {
		return $this->autoArchiveDuration;
	}
	
	/**
	 * timestamp when the thread's archive status was last changed, used for calculating recent activity
	 * changed when creating, archiving, or unarchiving a thread, and when changing the auto_archive_duration field.
	 *
	 * @return Timestamp
	 */
	public function getArchiveTimestamp(): Timestamp {
		return $this->archiveTimestamp;
	}
	
	/**
	 * @return Timestamp|null
	 */
	public function getCreateTimestamp(): ?Timestamp {
		return $this->createTimestamp;
	}
	
	/**
	 * @return bool
	 */
	public function isLocked(): bool {
		return $this->locked;
	}
	
	/**
	 * @return bool
	 */
	public function isInvitable(): bool {
		return $this->invitable;
	}
	
	/**
	 * @internal
	 *
	 * @param array $array
	 *
	 * @return ThreadMetadata
	 */
	public static function fromArray(array $array): ThreadMetadata {
		return new ThreadMetadata($array['archived'], $array['auto_archive_duration'], Timestamp::fromDate($array['archive_timestamp']), $array['locked'], $array['invitable'] ?? true, (@$array['create_timestamp'] ? Timestamp::fromDate($array['create_timestamp']) : null));
	}
}