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

namespace phpcord\voice\websocket\packet;

use phpcord\voice\websocket\VoiceOpCodes;
use function microtime;
use function time;

class VoiceHeartbeatPacket extends VoicePacket {
	
	/** @var float $nonce */
	private float $nonce;
	
	/**
	 * @param float|null $customNonce
	 */
	public function __construct(?float $customNonce = null) {
		$this->nonce = $customNonce ?? (int) (microtime(true) * 1000);
	}
	
	public function getOpCode(): int {
		return VoiceOpCodes::HEARTBEAT();
	}
	
	public function getPayload(): float {
		return $this->nonce;
	}
}