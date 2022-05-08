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

namespace phpcord\voice;

use phpcord\exception\NotImplementedException;
use phpcord\logger\Logger;
use phpcord\runtime\tick\Ticker;
use phpcord\Version;
use phpcord\voice\websocket\packet\VoiceHeartbeatPacket;
use phpcord\voice\websocket\packet\VoiceIdentifyPacket;
use phpcord\voice\websocket\packet\VoicePacket;
use phpcord\runtime\network\socket\WebSocket;
use phpcord\runtime\tick\Tickable;
use phpcord\utils\InternetAddress;
use phpcord\voice\websocket\VoiceOpCodeHandlerMap;
use function json_decode;
use function stream_get_meta_data;
use function var_dump;

class VoiceConnection implements Tickable {
	
	/**
	 * The (TCP) websocket running as a sub socket that is NOT used to transfer voice data
	 * @var WebSocket $webSocket
	 */
	private WebSocket $webSocket;
	
	/** @var Logger $logger */
	private Logger $logger;
	
	/** @var int|null $heartbeatInterval */
	public ?int $heartbeatInterval = null;
	
	/** @var int|null $lastHeartbeat */
	public ?int $lastHeartbeat = null;
	
	/**
	 * @param string $endpointUrl
	 * @param string $token
	 * @param string $sessionId
	 * @param int $guildId
	 * @param int $userId
	 */
	public function __construct(private string $endpointUrl, private string $token, private string $sessionId, private int $guildId, private int $userId) {
		throw new NotImplementedException();
		$this->logger = new Logger('Voice(WS)');
		//var_dump('ENDPOINT: ' . $this->endpointUrl, 'TOKEN: ' . $this->token, 'SESSION_ID: ' . $this->sessionId, 'SERVER_ID: ' . $this->guildId, 'USER_ID: ' . $this->userId);
		$this->webSocket = new WebSocket(InternetAddress::fromString($this->endpointUrl), '/?v=' . Version::VOICE_GATEWAY_VERSION);
	}
	
	/**
	 * @return string
	 */
	public function getToken(): string {
		return $this->token;
	}
	
	/**
	 * @return string
	 */
	public function getSessionId(): string {
		return $this->sessionId;
	}
	
	/**
	 * @return int
	 */
	public function getGuildId(): int {
		return $this->guildId;
	}
	
	/**
	 * @return int
	 */
	public function getUserId(): int {
		return $this->userId;
	}
	
	public function sendPacket(VoicePacket $voicePacket): bool {
		return $this->write($voicePacket->encode());
	}
	
	public function write(string $buffer): bool {
		$this->logger->info('Sending ' . $buffer);
		return $this->webSocket->write($buffer);
	}
	
	public function tick(int $currentTick): void {
		while ($buffer = $this->webSocket->read()) {
			$this->logger->info('Received ' . $buffer);
			VoiceOpCodeHandlerMap::getInstance()->handle($this, json_decode($buffer, true));
		}
		if ($this->heartbeatInterval !== null and ($currentTick - $this->lastHeartbeat) >= $this->heartbeatInterval) $this->heartbeat();
	}
	
	public function heartbeat(): void {
		$this->sendPacket(new VoiceHeartbeatPacket());
		$this->lastHeartbeat = Ticker::getInstance()->getCurrentTick();
	}
}