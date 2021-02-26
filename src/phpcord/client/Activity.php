<?php

namespace phpcord\client;

use InvalidArgumentException;
use ReflectionClass;
use function array_merge;
use function json_encode;
use function strtolower;
use function strtoupper;

final class Activity {
	/** @var string $name the name of the new activity */
	protected $name;
	
	/** @var string $status, the status of the bot, e.g online, dnd */
	protected $status;
	
	/** @var array $data some additional data that might be used */
	protected $data = [];
	
	/** @var int $action */
	protected $action = null;
	
	/*
	 * Display the bot as online   (green)
	 */
	public const STATUS_ONLINE = "online";
	
	/*
	 * Display the bot as DND (Do Not Disturb)   (red)
	 */
	public const STATUS_DND = "dnd";
	
	/*
	 * Display the bot as Idle (afk)   (orange)
	 */
	public const STATUS_IDLE = "idle";
	
	/*
	 * Displays the bot as invisible   (gray)
	 */
	public const STATUS_INVISIBLE = "invisible";
	
	/*
	 * Displays the bot as offline   (gray)
	 */
	public const STATUS_OFFLINE = "offline";
	
	
	public const TYPE_PLAYING = 0;
	
	public const TYPE_STREAMING = 1;
	
	public const TYPE_LISTENING = 2;
	
	public const TYPE_CUSTOM = 4;
	
	public const TYPE_COMPETING = 5;
	
	/**
	 * Set the bot playing with a string as game
	 * Can be anything
	 *
	 * @api
	 *
	 * @param string $game
	 */
	public function setPlaying(string $game) {
		$this->action = self::TYPE_PLAYING;
		$this->name = $game;
	}
	
	/**
	 * Set the bot competing with a string as game
	 * Can be anything
	 *
	 * @api
	 *
	 * @param string $game
	 */
	public function setCompeting(string $game) {
		$this->action = self::TYPE_COMPETING;
		$this->name = $game;
	}
	/**
	 * Set the bot listening a string as song
	 * Can be anything
	 *
	 * @api
	 *
	 * @param string $song
	 */
	public function setListening(string $song) {
		$this->action = self::TYPE_LISTENING;
		$this->name = $song;
	}
	/**
	 * Set the bot streaming with a string as game
	 * Can be anything
	 *
	 * @api
	 *
	 * @param string $game
	 * @param string|null $url
	 */
	public function setStreaming(string $game, ?string $url = null) {
		$this->action = self::TYPE_STREAMING;
		$this->name = $game;
		
		if (!is_null($url)) $this->data["url"] = $url;
	}
	
	/**
	 * Applies a custom status to the status
	 *
	 * @api
	 *
	 * @param string $status
	 */
	public function setCustomStatus(string $status) {
		$this->action = self::TYPE_CUSTOM;
		$this->name = $status;
	}
	
	/**
	 * Set the create timestamp, only use this if you know what you're doing
	 *
	 * @api
	 *
	 * @param string $timestamp
	 */
	public function setCreateTimestamp(string $timestamp) {
		$this->data["created_at"] = $timestamp;
	}
	
	/**
	 * Sets the status for the bot.
	 * See all possibilities above
	 *
	 * @api
	 *
	 * @param string $status
	 */
	public function setStatus(string $status = self::STATUS_ONLINE) {
		$ref = new ReflectionClass($this);
		if (!$ref->getConstant("STATUS_" . strtoupper($status))) throw new InvalidArgumentException("Please enter a valid status!");
		
		$this->status = strtolower($status);
	}
	
	/**
	 * Encodes the Activity to a string that can be sent to discord over gateway
	 *
	 * @internal
	 *
	 * @return string
	 */
	public function encode(): string {
		$data = ["op" => 3, "d" => array_merge($this->data, ["status" => $this->status, "activities" => [ [ "type" => $this->action, "name" => $this->name ] ], "afk" => ($this->status === self::STATUS_IDLE) ])];
		if (!isset($data["d"]["since"])) $data["d"]["since"] = 91879201;
		return json_encode($data);
	}
}