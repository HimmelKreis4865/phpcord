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

namespace phpcord\runtime\network\packet;

use JetBrains\PhpStorm\Pure;
use function json_decode;
use function microtime;

class MessageBuffer {
	
	/** @var int $receiveTimestamp */
	protected int $receiveTimestamp;
	
	private ?array $decodedArray = null;
	
	/**
	 * @param string $buffer
	 * @param int|null $receiveTimestamp
	 */
	public function __construct(public string $buffer, ?int $receiveTimestamp = null) {
		$this->receiveTimestamp = $receiveTimestamp ?? (int) (microtime(true) * 1000);
	}
	
	public function getBuffer(): string {
		return $this->buffer;
	}
	
	public function asArray(): array {
		if ($this->decodedArray) return $this->decodedArray;
		return ($this->decodedArray = json_decode($this->buffer, true));
	}
	
	#[Pure] public function packetIntent(): IntentMessageBuffer {
		return IntentMessageBuffer::fromMessageBuffer($this);
	}
	
	public function __toString(): string {
		return $this->buffer;
	}
	
	/**
	 * @return int
	 */
	public function getReceiveTimestamp(): int {
		return $this->receiveTimestamp;
	}
}