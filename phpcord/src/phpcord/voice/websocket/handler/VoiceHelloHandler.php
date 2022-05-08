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

namespace phpcord\voice\websocket\handler;

use phpcord\runtime\tick\Ticker;
use phpcord\Version;
use phpcord\voice\VoiceConnection;
use phpcord\voice\websocket\packet\VoiceIdentifyPacket;
use RuntimeException;
use function var_dump;

class VoiceHelloHandler extends VoiceOpCodeHandler {
	
	/**
	 * @param VoiceConnection $connection
	 * @param array $payload
	 *
	 * @return void
	 */
	public function handle(VoiceConnection $connection, array $payload): void {
		$data = $payload['d'];
		if ($data['v'] !== Version::VOICE_GATEWAY_VERSION) throw new RuntimeException('Voice gateway version ' . $data['v'] . ' is not supported. Current version: ' . Version::VOICE_GATEWAY_VERSION);
		
		if (!isset($data['heartbeat_interval'])) return;
		$connection->heartbeatInterval = ($data['heartbeat_interval'] / Ticker::MS_PER_TICK - 10);
		$connection->lastHeartbeat = Ticker::getInstance()->getCurrentTick();
		$connection->heartbeat();
		$connection->sendPacket(new VoiceIdentifyPacket($connection->getGuildId(), $connection->getUserId(), $connection->getSessionId(), $connection->getToken()));
	}
}