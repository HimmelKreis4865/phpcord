<?php

namespace phpcord\stream;

use phpcord\thread\Thread;
use function var_dump;

class SocketWriteThread extends Thread {
	
	protected $converter;
	
	protected $ws;
	
	public function __construct(ThreadConverter $converter, $ws) {
		$this->converter = $converter;
		$this->ws = $ws;
	}
	
	public function onRun() {
		$ws = WebSocket::fromStream($this->ws);
		var_dump($ws);
		while ($this->converter->running) {
			foreach ($this->converter->pushMainToThread as $k => $message) {
				$ws->write($message);
				unset($this->converter->pushMainToThread[$k]);
			}
		}
		var_dump("stopped? :C");
	}
}