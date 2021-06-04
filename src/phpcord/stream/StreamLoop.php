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
	
	public const PREFIX_INVALIDATE = "--invalidate";
	
	public const MAX_FAILURES = 5;
	
	public $lastACK;
	
	protected $failureCount = 0;

	protected $converter;
	
	public function __construct(ThreadConverter $converter) {
		$this->converter = $converter;
	}
	
	public function onRun() {
		// todo: dynamic autoload for threads
		$ws = new WebSocket(self::GATEWAY, 443);
		$thread = new SocketWriteThread($this->converter, $ws->stream);
		$thread->start();
		while (true) {
			usleep(1000 * 50);
			if ($ws->isInvalid()) {
				$ws->close();
				$ws = new WebSocket(self::GATEWAY, 443);
				$this->converter->running = false;
				usleep(1000 * 50);
				$this->converter->running = true;
				$thread2 = new SocketWriteThread($this->converter, $ws->stream);
				$thread2->start();
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
			$this->converter->pushThreadToMain[] = $message;
		}
	}
}