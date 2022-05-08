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

use JetBrains\PhpStorm\ArrayShape;
use phpcord\runtime\network\opcode\Opcodes;

/**
 * @internal
 */
class ResumePacket extends Packet {
	
	/**
	 * @param string $token
	 * @param string $sessionId
	 * @param int $lastSeq
	 */
	public function __construct(private string $token, private string $sessionId, private int $lastSeq) { }
	
	/**
	 * @return int
	 */
	public function getOpCode(): int {
		return Opcodes::RESUME();
	}
	
	#[ArrayShape(['token' => "string", 'session_id' => "string", 'seq' => "int"])] public function getPayload(): array {
		return [
			'token' => $this->token,
			'session_id' => $this->sessionId,
			'seq' => $this->lastSeq
		];
	}
}