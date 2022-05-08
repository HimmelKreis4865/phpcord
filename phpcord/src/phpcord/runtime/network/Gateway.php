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

namespace phpcord\runtime\network;

use phpcord\event\client\ClientPingUpdateEvent;
use phpcord\runtime\network\opcode\OpCodePool;
use phpcord\runtime\network\packet\HeartbeatPacket;
use phpcord\runtime\network\packet\MessageBuffer;
use phpcord\runtime\network\packet\Packet;
use phpcord\runtime\network\socket\WebSocket;
use phpcord\runtime\tick\Ticker;
use phpcord\scheduler\Scheduler;
use phpcord\scheduler\TaskHandler;
use phpcord\utils\InternetAddress;
use phpcord\Version;
use RuntimeException;
use function floor;
use function json_decode;
use function microtime;
use function var_dump;

final class Gateway implements MessageSender {
	
	public const ADDRESS = 'gateway.discord.gg';
	
	public const PORT = 443;
	
	public const DEFAULT_PATH = '/?v=' . Version::GATEWAY_VERSION . '&encoding=json';
	
	private const MAXIMUM_FAILURES_PER_INTERVAL = 3;
	
	private const FAILURE_INTERVAL = 40 * 5;
	
	/** @var WebSocket $webSocket */
	private WebSocket $webSocket;
	
	/** @var SessionDetails $sessionDetails */
	private SessionDetails $sessionDetails;
	
	/** @var int $ping */
	private int $ping = 0;
	
	/** @var float $sendTimestamp */
	private float $sendTimestamp = 0.0;
	
	/** @var int $seqNum */
	private int $seqNum = 0;
	
	/** @var bool $connected */
	private bool $connected = false;
	
	/** @var TaskHandler|null $heartbeatHandler */
	private ?TaskHandler $heartbeatHandler = null;
	
	private int $failureCount = 0;
	
	public function __construct() {
		$this->open();
		$this->sessionDetails = new SessionDetails();
		
		Network::getInstance()->registerListener(function (MessageSender $sender, MessageBuffer $buffer): void {
			if (!($data = json_decode($buffer, true))) Network::getInstance()->getLogger()->error('Invalid payload ' . $buffer . ' encountered and could not be decoded');
			if (isset($data['s']) and $data['s']) $this->seqNum = $data['s'];
			OpCodePool::getInstance()->run($sender, $buffer);
		});
	}
	
	public function open(): void {
		Network::getInstance()->getLogger()->notice('Connecting to the websocket...');
		if (isset($this->webSocket)) $this->webSocket->close();
		$this->webSocket = new WebSocket(new InternetAddress(Gateway::ADDRESS, Gateway::PORT), Gateway::DEFAULT_PATH);
		$this->connected = true;
	}
	
	public function close(): void {
		$this->webSocket->close();
		$this->connected = false;
	}
	
	/**
	 * @return SessionDetails
	 */
	public function getSessionDetails(): SessionDetails {
		return $this->sessionDetails;
	}
	
	/**
	 * @return void
	 */
	public function resetSessionDetails(): void {
		$this->sessionDetails = new SessionDetails();
	}
	
	/**
	 * @internal
	 *
	 * @return void
	 */
	public function heartbeat(): void {
		$this->sendTimestamp = microtime(true) * 1000;
		$this->sendPacket(new HeartbeatPacket($this->getLastSequence()));
	}
	
	/**
	 * @internal
	 *
	 * @return void
	 */
	public function startHeartbeatTask(): void {
		$this->heartbeatHandler?->cancel();
		// subtraction of 50 is needed due to a bad sleep timing on Windows
		$this->heartbeatHandler = Scheduler::getInstance()->repeating(fn() => $this->heartbeat(), (floor($this->sessionDetails->heartbeatInterval / Ticker::MS_PER_TICK) - 350));
	}
	
	public function onHeartbeatACK(MessageBuffer $buffer): void {
		$ping = floor($buffer->getReceiveTimestamp() - $this->sendTimestamp - 25);
		($ev = new ClientPingUpdateEvent($ping))->call();
		if (!$ev->isCancelled()) $this->ping = $ping;
	}
	
	/**
	 * @return int
	 */
	public function getPing(): int {
		return $this->ping;
	}
	
	public function receive(): MessageBuffer|false {
		$buffer = $this->webSocket->read();
		if ($buffer) Network::getInstance()->getLogger()->debug('Received ' . $buffer);
		return $buffer;
	}
	
	public function sendPacket(Packet $packet): bool {
		return $this->write($packet->encode($this->getLastSequence()));
	}
	
	public function write(string $buffer): bool {
		Network::getInstance()->getLogger()->debug('Sending ' . $buffer);
		return $this->webSocket->write($buffer);
	}
	
	public function validate(): bool {
		if ((Ticker::getInstance()->getCurrentTick() % self::FAILURE_INTERVAL) === 0) $this->failureCount = 0;
		if (!$this->webSocket->isAlive() and $this->connected) {
			$this->onConnectionLoss();
			$this->open();
			return false;
		}
		return $this->webSocket->isAlive();
	}
	
	private function onConnectionLoss(): void {
		if (++$this->failureCount >= self::MAXIMUM_FAILURES_PER_INTERVAL)
			throw new RuntimeException('Could not establish a connection to the WebSocket.');
		Network::getInstance()->getLogger()->warning('WebSocket connection gone, reconnecting...');
	}
	
	/**
	 * @return int
	 */
	public function getLastSequence(): int {
		return $this->seqNum;
	}
}