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

use JetBrains\PhpStorm\ArrayShape;
use phpcord\voice\websocket\VoiceOpCodes;

class VoiceIdentifyPacket extends VoicePacket {
	
	/**
	 * @param int $serverId
	 * @param int $userId
	 * @param string $sessionId
	 * @param string $token
	 */
	public function __construct(private int $serverId, private int $userId, private string $sessionId, private string $token) { }
	
	#[ArrayShape(['server_id' => "string", 'user_id' => "string", 'session_id' => "string", 'token' => "string"])]
	public function getPayload(): array {
		return [
			'server_id' => (string) $this->serverId,
			'user_id' => (string) $this->userId,
			'session_id' => $this->sessionId,
			'token' => $this->token
		];
	}
	
	public function getOpCode(): int {
		return VoiceOpCodes::IDENTIFY();
	}
}