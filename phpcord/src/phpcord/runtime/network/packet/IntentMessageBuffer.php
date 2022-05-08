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

final class IntentMessageBuffer extends MessageBuffer {
	
	/**
	 * @param string $buffer
	 * @param int|null $receiveTimestamp
	 */
	#[Pure] public function __construct(string $buffer, ?int $receiveTimestamp = null) {
		parent::__construct($buffer, $receiveTimestamp);
	}
	
	public function name(): ?string {
		return @$this->asArray()['t'];
	}
	
	public function data(): array {
		return $this->asArray()['d'];
	}
	
	/**
	 * @param MessageBuffer $buffer
	 *
	 * @return IntentMessageBuffer
	 */
	#[Pure] public static function fromMessageBuffer(MessageBuffer $buffer): IntentMessageBuffer {
		return new IntentMessageBuffer($buffer->getBuffer(), $buffer->getReceiveTimestamp());
	}
}