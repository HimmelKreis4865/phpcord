<?php

namespace phpcord\client;

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
	
	
	public function setPlaying(string $game) {
		$this->action = self::TYPE_PLAYING;
		$this->name = $game;
	}
	
	public function setCompeting(string $game) {
		$this->action = self::TYPE_COMPETING;
		$this->name = $game;
	}
	
	public function setListening(string $song) {
		$this->action = self::TYPE_LISTENING;
		$this->name = $song;
	}
	
	public function setStreaming(string $game, ?string $url) {
		$this->action = self::TYPE_STREAMING;
		$this->name = $game;
		
		if (!is_null($url)) $this->data["url"] = $url;
	}
	
	public function setCustomStatus(string $status) {
		$this->action = self::TYPE_CUSTOM;
		$this->name = $status;
	}
	
	public function setCreateTimestamp(string $timestamp) {
		$this->data["created_at"] = $timestamp;
	}
	
	public function setStatus(string $status = self::STATUS_ONLINE) {
		$ref = new \ReflectionClass($this);
		if (!$ref->getConstant("STATUS_" . strtoupper($status))) throw new \InvalidArgumentException("Please enter a valid status!");
		
		$this->status = strtolower($status);
	}
	
	public function encode(): string {
		$data = ["op" => 3, "d" => array_merge($this->data, ["status" => $this->status, "activities" => [ [ "type" => $this->action, "name" => $this->name ] ], "afk" => ($this->status === self::STATUS_IDLE) ])];
		if (!isset($data["d"]["since"])) $data["d"]["since"] = 91879201;
		return json_encode($data);
	}
}


