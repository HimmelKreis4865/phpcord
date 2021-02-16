<?php

namespace phpcord\stream;

use phpcord\connection\ConnectOptions;
use phpcord\connection\ConvertManager;
use phpcord\Discord;
use phpcord\utils\MainLogger;
use Throwable;
use function stream_context_create;
use function var_dump;

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

		$context = ((count(Discord::getInstance()->sslSettings) > 0) ? stream_context_create(["ssl" => Discord::getInstance()->sslSettings]) : null);
		$handler->connect("gateway.discord.gg", 443, [], 1, true, $context);

		while (true) {
			if ($handler->isExpired()) return;
			try {
				$result = $handler->read();
			} catch (Throwable $exception) {
				var_dump("ERROR: " . $exception->getMessage());
				return;
			}

			if (!is_bool($result) and !is_int($result) and strlen($result) > 0) {
				$encoded = json_decode($result, true);
				MainLogger::logDebug("received a raw message: " . $result);

				if (@$encoded["s"] !== null) $this->lastS = @$encoded["s"];

				if ($encoded["op"] === 10) {
					$this->manager->heartbeat_interval = $encoded["d"]["heartbeat_interval"];
					$handler->write('{ "op": 2, "d": { "token": "' . $this->options->getToken() . '", "intents": ' . $this->options->getIntents() . ', "properties": { "$os": "%2%", "$browser": "DiscordPHP", "$device": "DiscordPHP" } } }');
					$this->manager->last_heartbeat = microtime(true);
				}

				if ($this->onSend !== null) {
					$callable = $this->onSend;
					$manager = $this->manager;
					$callable($manager, $handler, $result);
					$this->manager->heartbeat_interval = $manager->heartbeat_interval;
				}
			}
			
			Discord::getInstance()->onUpdate($handler);
			
			if ($this->manager->heartbeat_interval !== null) {
				if (((microtime(true) - $this->manager->last_heartbeat) * 1000) >= ($this->manager->heartbeat_interval - 1500)) {
					MainLogger::logDebug("Sent heartbeat after " . ((microtime(true) - $this->manager->last_heartbeat) * 1000) . "ms");
					$handler->write(json_encode(["op" => 1, "d" => $this->lastS]));
					$this->manager->last_heartbeat = microtime(true);
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

	public function end() {
		$this->closed = true;
	}
}


