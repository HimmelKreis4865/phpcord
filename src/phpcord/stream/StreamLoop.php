<?php

namespace phpcord\stream;

use phpcord\thread\Thread;
use phpcord\utils\ArrayUtils;
use phpcord\utils\LogStore;
use phpcord\utils\MainLogger;
use RuntimeException;
use function array_merge;
use function json_decode;
use function json_encode;
use function microtime;
use function usleep;
use function var_dump;
use const PTHREADS_INHERIT_CLASSES;
use const PTHREADS_INHERIT_NONE;

class StreamLoop extends Thread {
	
	public const GATEWAY = "ssl://gateway.discord.gg";
	
	public const PREFIX_INVALIDATE = "--invalidate";
	
	public const MAX_FAILURES = 5;
	
	public $lastACK;
	
	protected $failureCount = 0;

	protected $converter;
	
	protected $settings;
	
	public function __construct(ThreadConverter $converter, array $settings = []) {
		$this->converter = $converter;
		$this->settings = $settings;
	}
	
	public function onRun() {
		$ws = new WebSocket(self::GATEWAY, 443, false, true, ArrayUtils::asArray($this->settings));
		$thread = new SocketWriteThread($this->converter, $ws->stream);
		$thread->start();
		
		var_dump("file:" . LogStore::$logFile);
		
		while (true) {
			usleep(1000 * 50);
			if ($ws->isInvalid()) {
				$ws->close();
				$ws = new WebSocket(self::GATEWAY, 443, false, true, ArrayUtils::asArray($this->settings));
				$thread->converter->running = false;
				usleep(60 * 1000);
				$this->converter->running = true;
				unset($thread);
				$thread = new SocketWriteThread($this->converter, $ws->stream);
				$thread->start();
				// this will be thrown once for example there's a wifi downtime or smh - keeping it away from spamming useless reconnects
				// won't be thrown if the socket is working and there are messages that can be received
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
			if (($data = @json_decode($message, true)) and isset($data["op"]) and $data["op"] === 11) {
				$message = json_encode(array_merge($data, ["recv_time" => microtime(true)]));
			}
			$this->converter->pushThreadToMain[] = $message;
		}
	}
}