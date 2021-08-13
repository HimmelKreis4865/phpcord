<?php

namespace phpcord\stream;

use phpcord\thread\Thread;
use phpcord\utils\MainLogger;
use RuntimeException;
use function usleep;

class SocketWriteThread extends Thread {
	
	public $converter;
	
	protected $ws;
	
	public function __construct(ThreadConverter $converter, $ws) {
		$this->converter = $converter;
		$this->ws = $ws;
	}
	
	public function onRun() {
		$ws = WebSocket::fromStream($this->ws);
		$failureCount = 0;
		
		while ($this->converter->running) {
			usleep(1000 * 25);
			foreach ($this->converter->pushMainToThread as $k => $message) {
				if (!$ws->write($message)) {
					MainLogger::logWarning("Failed to write to websocket!");
					if ($failureCount > StreamLoop::MAX_FAILURES)
						throw new RuntimeException("Maximum amount of write errors exceeded");
				}
				unset($this->converter->pushMainToThread[$k]);
			}
		}
	}
}