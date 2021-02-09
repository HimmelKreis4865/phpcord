<?php

namespace phpcord\connection;

use phpcord\Discord;
use phpcord\stream\StreamHandler;
use phpcord\stream\StreamLoop;
use phpcord\utils\InstantiableTrait;
use phpcord\utils\MainLogger;

final class ConnectionHandler {
	use InstantiableTrait;

	/** @var ConnectOptions $options */
	protected $options;
	/** @var ConvertManager $connection */
	public $connectionManager;
	/** @var Discord $discord */
	private $discord;
	/** @var null | StreamHandler $handler */
	public $handler = null;

	public function __construct() {
	    self::$instance = $this;
    }

    /**
	 * @param Discord $discord
	 * @param ConnectOptions $connectOptions
     */
    public function startConnection(Discord $discord, ConnectOptions $connectOptions): void {
    	$this->options = $connectOptions;
        $this->startListener($discord);
    }

	public function startListener(Discord $discord) {
    	$this->connectionManager = new ConvertManager();
    	$this->connectionManager->discord = $discord;
		MainLogger::logInfo("§aStartup complete! §rNow waiting for incoming packets...");
		$connection = new StreamLoop($this->connectionManager, $this->options, function(ConvertManager $manager, StreamHandler $stream, string $message) {
			$manager->discord->handle($message, $manager, $stream);
		});
		$connection->start();
	}

	public function close() {

	}
}


