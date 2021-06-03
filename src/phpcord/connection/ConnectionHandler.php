<?php

namespace phpcord\connection;

use phpcord\Discord;
use phpcord\stream\StreamLoop;
use phpcord\stream\WebSocket;
use phpcord\utils\InstantiableTrait;
use phpcord\utils\MainLogger;
use function var_dump;

final class ConnectionHandler {
	use InstantiableTrait;
	
    public function startConnection(): void {
        $this->startListener();
    }
	
	/**
	 * Starts the listener for a Discord instance
	 *
	 * @internal
	 */
	public function startListener() {
		MainLogger::logInfo("§aStartup complete! §rNow waiting for incoming packets...");
		$connection = new StreamLoop();
		$connection->start();
	}
}