<?php

namespace phpcord\stream;

use phpcord\thread\Thread;
use phpcord\utils\MainLogger;
use RuntimeException;
use function json_decode;
use function json_encode;
use function usleep;
use function var_dump;

class StreamLoop extends Thread {
	
	public const GATEWAY = "ssl://gateway.discord.gg";
	
	public const MAX_FAILURES = 5;
	
	public $lastACK;
	
	protected $failureCount = 0;

	public function onRun() {
		$opCodeHandler = new OPCodeHandler();
		
		// todo: dynamic autoload for threads
		$ws = new WebSocket(self::GATEWAY, 443);

		while (true) {
			usleep(1000 * 50);
			if ($ws->isInvalid()) {
				$ws->close();
				$ws = new WebSocket(self::GATEWAY, 443);
				if (++$this->failureCount > self::MAX_FAILURES)
					throw new RuntimeException("Failed to reconnect the gateway connection!");
			}
			if (!($message = $ws->read())) {
				MainLogger::logDebug("Failed to read, reconnecting");
				$ws->invalidate();
				continue;
			}
			$this->failureCount = 0;
			MainLogger::logDebug("Received $message");
			$data = json_decode($message, true);
			if (!$data or !isset($data["op"])) return;
			$opCodeHandler->{"__" . $data["op"]}($ws, $this, $data);
			
			if (QueuedSender::getInstance()->has()) {
				foreach (QueuedSender::getInstance()->getFullQueue() as $buffer) {
					$ws->write($buffer);
				}
			}
		}
	}
	
	public function identify(WebSocket $socket): void {
		// todo: change this
		$socket->write(json_encode(["op" => 2, "d" => ["token" => "", "intents" => 513, "properties" => ["\$os" => "linux", "\$browser" => "my_library", "\$device" => "my_library"]]]));
	}
}