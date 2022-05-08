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

use phpcord\runtime\network\opcode\Opcodes;

class HeartbeatPacket extends Packet {
	
	/**
	 * @param int $lastSequence
	 */
	public function __construct(private int $lastSequence) { }
	
	public function getOpCode(): int {
		return Opcodes::HEARTBEAT();
	}
	
	public function getPayload(): string {
		return $this->lastSequence;
	}
}