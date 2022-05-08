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

namespace phpcord\utils;

use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;
use JsonSerializable;
use function date;
use function strtotime;

final class Timestamp implements JsonSerializable {
	
	private function __construct(private int $timestamp) { }
	
	/**
	 * @param string $format
	 *
	 * @return string
	 */
	public function format(string $format): string {
		return date($format, $this->timestamp);
	}
	
	public function diff(Timestamp $timestamp): int {
		return abs($timestamp->timestamp - $this->timestamp);
	}
	
	/**
	 * Returns whether the timestamp is a time in the past or not
	 *
	 * @return bool
	 */
	public function inPast(): bool {
		return ($this->timestamp < time());
	}
	
	/**
	 * @param string $date
	 *
	 * @return Timestamp
	 */
	public static function fromDate(string $date): Timestamp {
		if (!$timestamp = strtotime($date)) throw new InvalidArgumentException('Date ' . $date . ' could not be converted to a timestamp!');
		return new Timestamp($timestamp);
	}
	
	#[Pure] public static function fromSnowflake(int $snowflake): Timestamp {
		return Timestamp::fromTimestamp((($snowflake >> 22) + 1420070400000) / 1000);
	}
	
	/**
	 * @param int $timestamp
	 *
	 * @return Timestamp
	 */
	#[Pure] public static function fromTimestamp(int $timestamp): Timestamp {
		return new Timestamp($timestamp);
	}
	
	public static function now(): Timestamp {
		return Timestamp::fromTimestamp(time());
	}
	
	public function jsonSerialize(): int {
		return $this->timestamp;
	}
}