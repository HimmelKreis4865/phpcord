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

namespace phpcord\utils\presence;

use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;
use phpcord\utils\enum\EnumTrait;

/**
 * @method static Status ONLINE()
 * @method static Status DND()
 * @method static Status IDLE()
 * @method static Status INVISIBLE()
 * @method static Status OFFLINE()
 */
final class Status implements JsonSerializable {
	use EnumTrait;
	
	/** @var Activity|null $activity */
	private ?Activity $activity = null;
	
	/** @var bool $afk */
	private bool $afk = false;
	
	/** @var int|null $timestamp */
	private ?int $timestamp = null;
	
	protected static function make(): void {
		self::register('ONLINE', new Status('online'));
		self::register('DND', new Status('dnd'));
		self::register('IDLE', new Status('idle'));
		self::register('INVISIBLE', new Status('invisible'));
		self::register('OFFLINE', new Status('offline'));
	}
	
	/**
	 * @param string $status
	 */
	public function __construct(private string $status) { }
	
	/**
	 * @return Status
	 *
	 * @return Status
	 */
	public function setAfk(): Status {
		$this->afk = true;
		return $this;
	}
	
	/**
	 * @param int $timestamp
	 * A unix timestamp in milliseconds
	 *
	 * @return Status
	 */
	public function setSince(int $timestamp): Status {
		$this->timestamp = $timestamp;
		return $this;
	}
	
	/**
	 * @param Activity|null $activity
	 *
	 * @return Status
	 */
	public function setActivity(?Activity $activity): Status {
		$this->activity = $activity;
		return $this;
	}
	
	#[ArrayShape(['since' => "int|null", 'afk' => "bool", 'status' => "string", 'activities' => "null[]|\phpcord\utils\presence\Activity[]"])]
	public function jsonSerialize(): array {
		return [
			'since' => $this->timestamp,
			'afk' => $this->afk,
			'status' => $this->status,
			'activities' => [
				$this->activity
			]
		];
	}
}