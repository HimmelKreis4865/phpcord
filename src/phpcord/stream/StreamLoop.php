<?php

namespace phpcord\stream;

use phpcord\connection\ConnectOptions;
use phpcord\connection\ConvertManager;
use phpcord\Discord;
use phpcord\utils\MainLogger;
use RuntimeException;
use function array_merge;
use function count;
use function error_get_last;
use function is_string;
use function json_decode;
use function json_encode;
use function stream_context_create;
use function strlen;
use function usleep;

class StreamLoop {
	/** @var callable $onSend */
	protected $onSend;

	/** @var bool $closed */
	private $closed = false;

	/** @var StreamHandler $handler */
	public $handler;
	
	/** @var ConvertManager $manager */
	public $manager;

	/** @var ConnectOptions $data */
	private $options;
	
	/** @var null $lastS */
	private $lastS = null;

	/**
	 * StreamLoop constructor.
	 *
	 * @param ConvertManager $manager
	 * @param ConnectOptions $options
	 * @param callable|null $onSend
	 */
	public function __construct(ConvertManager $manager, ConnectOptions $options, ?callable $onSend) {
		$this->manager = $manager;
		$this->onSend = $onSend;
		$this->options = $options;
		$this->handler = new StreamHandler();

	}

	public function run() {
		$handler = $this->handler;

		$context = ((count(Discord::getInstance()->sslSettings) > 0) ? stream_context_create(["ssl" => array_merge(Discord::getInstance()->sslSettings, [ "SNI_enabled" => true, "peer_name" => "gateway.discord.gg", "SNI_server_name" => "gateway.discord.gg", "CN_match" => "gateway.discord.gg"])]) : null);
		$handler->connect("gateway.discord.gg", 443, [], 1, true, $context);

		while (!$this->closed) {
			Discord::getInstance()->onUpdate($handler);
			
			if ($handler->isExpired()) {
				MainLogger::logDebug("Reconnecting to the gateway...");
				$handler->close();
				$handler = new StreamHandler();
				$context = ((count(Discord::getInstance()->sslSettings) > 0) ? stream_context_create(["ssl" => array_merge(Discord::getInstance()->sslSettings, [ "SNI_enabled" => true, "peer_name" => "gateway.discord.gg", "SNI_server_name" => "gateway.discord.gg", "CN_match" => "gateway.discord.gg"])]) : null);
				if (!$handler->connect("gateway.discord.gg", 443, [], 1, true, $context)) {
					throw new RuntimeException("Failed to reconnect to discord gateway!");
				}
				continue;
			}
			
			if (((microtime(true) - $this->manager->last_heartbeat) * 1000) >= ($this->manager->heartbeat_interval - 3000)) {
				MainLogger::logDebug("[WebSocket] Sent heartbeat after " . ((microtime(true) - $this->manager->last_heartbeat) * 1000) . "ms #{$this->heartbeatCount}");
				$this->heartbeat($handler);
			}
			
			$input = $handler->read();
			
			if (is_string($input) and strlen($input) > 1) {
				MainLogger::logDebug("[WebSocket] Received: " . $input);
				$parsed = json_decode($input, true);
				
				if (@$parsed["s"] !== null) $this->lastS = $parsed["s"];
				switch ($parsed["op"] ?? -1) {
					case 10:
						$this->manager->heartbeat_interval = $parsed["d"]["heartbeat_interval"];
						$handler->write(json_encode([ "op" => 2, "d" => ["token" => $this->options->getToken(), "intents" => $this->options->getIntents(), "properties" => [ "\$os" => "phpcord", "\$browser" => "phpcord", "\$" => "phpcord" ]] ]));
						$this->heartbeat($handler);
						break;
						
					default:
						if ($this->onSend !== null) {
							$callable = $this->onSend;
							$callable($this->manager, $handler, $input);
						}
						break;
				}
			}
		}
	}
	public function close() {
		$this->closed = true;
	}
	
	public function start() {
		$this->run();
	}
	
	public $heartbeatCount = 0;
	
	protected function heartbeat(StreamHandler $handler) {
		$this->heartbeatCount++;
		$this->manager->last_heartbeat = microtime(true);
		$handler->write(json_encode(["op" => 1, "d" => $this->lastS]));
		
		if ($this->heartbeatCount > 30) {
			$this->heartbeatCount = 0;
			$handler->expire();
		}
	}

	public function end() {
		$this->closed = true;
	}
}