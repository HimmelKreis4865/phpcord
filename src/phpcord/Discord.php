<?php

namespace phpcord;

use phpcord\command\CommandMap;
use phpcord\command\SimpleCommandMap;
use phpcord\connection\ConnectionHandler;
use phpcord\client\Client;
use phpcord\connection\ConnectOptions;
use phpcord\connection\ConvertManager;
use phpcord\event\client\ClientReadyEvent;
use phpcord\event\Event;
use phpcord\event\EventListener;
use phpcord\exception\ClientException;
use phpcord\guild\MessageSentPromise;
use phpcord\http\RestAPIHandler;
use phpcord\intents\IntentReceiveManager;
use phpcord\stream\StreamHandler;
use phpcord\utils\ClientInitializer;
use phpcord\utils\MainLogger;
use phpcord\utils\PermissionIds;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionMethod;
use function count;
use function floor;
use function json_encode;
use function memory_get_peak_usage;
use function memory_get_usage;
use function microtime;
use function set_time_limit;
use function var_dump;

final class Discord {
	/** @var int the version that is used for the gateway and restapi */
	public const VERSION = 8;

	/** @var null|Client $client */
	public $client;

	/** @var array $options */
	private $options;

	/** @var bool $debugMode */
	public static $debugMode = false;

	/** @var array $listeners */
	public static $listeners = [];

	/** @var int $intents */
	protected $intents = 513;

	/** @var MessageSentPromise[] $answerHandlers */
	public $answerHandlers = [];

	/** @var string $basedir */
	protected $basedir;
	
	/** @var self|null $lastInstance */
	public static $lastInstance;

	private $loggedIn = false;
	
	/** @var IntentReceiveManager $intentReceiveManager */
	public $intentReceiveManager;
	
	/** @var int $heartbeat_interval */
	public $heartbeat_interval;
	
	/** @var null|CommandMap $commandMap */
	private $commandMap = null;
	
	/** @var array $toSend */
	public $toSend = [];
	
	/** @var int|mixed $cacheLevel */
	public static $cacheLevel = 0;

	public function __construct(string $basedir, array $options = []) {
		self::$lastInstance = $this;
		$this->basedir = $basedir;
		//$this->setErrorHandler();
		$this->registerAutoload();
		$this->client = new Client();
		MainLogger::logInfo("Starting discord application...");
		$this->options = $options;
		PermissionIds::initDefinitions();
		if (isset($options["debugMode"]) and is_bool($options["debugMode"])) self::$debugMode = $options["debugMode"];
		MainLogger::logInfo("Loading intent receive manager...");
		if (isset($options["intents"]) and is_int($options["intents"])) $this->setIntents($options["intents"]);
	    $this->intentReceiveManager = new IntentReceiveManager();
	    if (isset($options["cache_level"])) self::$cacheLevel = $options["cache_level"];
		MainLogger::logInfo("§aLoading complete, waiting for a login now...");
    }
	
	/**
	 * Changes the intents to another number
	 *
	 * @api
	 *
	 * @param int $intents
	 */
	public function setIntents(int $intents = 513) {
		$this->intents = $intents;
	}
	
	/**
	 * Enabled the CommandMap, if you don't enable it, you won't be able to access it
	 *
	 * @api
	 */
    public function enableCommandMap(): void {
		$this->commandMap = new SimpleCommandMap();
	}

	/**
	 * CommandMap needs to be enabled for this action: @see enableCommandMap()
	 *
	 * @api
	 *
	 * @return CommandMap|null
	 */
	public function getCommandMap(): CommandMap {
		if ($this->commandMap instanceof CommandMap) return $this->commandMap;
		throw new InvalidArgumentException("You can't access the commandmap without activating it!");
	}

    /**
	 * Tries to login to discord gateway
	 *
	 * @api
	 *
	 * @param string|null $token
	 *
	 * @return void
	 *
	 * @throws ClientException
	 */
	public function login(string $token = null): void {
		set_time_limit(0);
		MainLogger::logInfo("Logging in...");
		if ($this->loggedIn) throw new ClientException("Could not connect to an already connected client!");

		if (is_null($token) and !isset($this->options["token"])) throw new \BadMethodCallException("Couldn't login to Discord since there is no token specified");

		$token = $token ?? $this->options["token"];

		$this->loggedIn = true;

		$this->intentReceiveManager->init();
		MainLogger::logInfo("Authenticating REST API...");
		RestAPIHandler::getInstance()->setAuth($token);
		$connectionHandler = new ConnectionHandler();
		MainLogger::logInfo("Starting websocket client...");
		$connectionHandler->startConnection($this, new ConnectOptions($token, $this->intents));
	}
	
	/**
	 * Registers Events on an EventListener subclass
	 *
	 * @api
	 *
	 * @param string|EventListener $eventListener
	 *
	 * @throws \ReflectionException
	 */
	public function registerEvents($eventListener) {
		if (is_string($eventListener)) $eventListener = new $eventListener();
		$ref = new ReflectionClass($eventListener);
		foreach ($ref->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
			if ($method->isStatic() or !$method->getDeclaringClass()->implementsInterface(EventListener::class) or $method->getNumberOfParameters() !== 1) continue;
			$event = $method->getParameters()[0]->getClass();
			if ($event === null or !$event->isSubclassOf(Event::class)) continue;
			$this->registerEvent($eventListener, $method->getName(), $event->getName());
		}
	}


	/**
	 * Registers an event-class string to a listener
	 *
	 * @api
	 *
	 * @param EventListener $listener
	 * @param string $method_name
	 * @param string $event
	 */
	public static function registerEvent(EventListener $listener, string $method_name, string $event) {
		if (!is_subclass_of($event, Event::class)) return;
		self::$listeners[$event][] = [$listener, $method_name];
	}
	
	/**
	 * Registers the autoload to remove includes
	 *
	 * @internal
	 */
	public function registerAutoload() {
		spl_autoload_register(function($class) {
			if (!class_exists($class)) require_once $this->basedir . str_replace("phpcord", "\src\phpcord", (strpos($class, DIRECTORY_SEPARATOR) === false ? "\u{005C}" . $class : $class)) . ".php";
		});
	}
	
	/**
	 * @internal
	 *
	 * @param string $message
	 * @param ConvertManager $manager
	 * @param StreamHandler $stream
	 *
	 * @throws exception\EventException
	 */
	public function handle(string $message, ConvertManager &$manager, StreamHandler $stream) {
		$this->registerAutoload();
		$message = json_decode($message, true);
		$interval = null;
		switch (intval($message["op"])) {
			case 10:
				$this->heartbeat_interval = $message["d"]["heartbeat_interval"];
				$manager->heartbeat_interval = $this->heartbeat_interval;
				break;

			case 0:
				if ($message["t"] === "GUILD_CREATE") {
					$client = $this->client ?? new Client();
					new ClientInitializer($client, $message["d"]);
					$event = new ClientReadyEvent($client);
					$event->call();
					if ($event->isCancelled()) return;
					$this->client = $client;
					
				} else if ($message["t"] === "READY") {
					$this->client->user = ClientInitializer::createBotUser($message["d"]);
				}
				$this->intentReceiveManager->executeIntent($this, $message["t"], $message["d"]);
				break;

			case 11:
				$last = $manager->last_heartbeat ?? microtime(true);

				if ($this->client !== null) $this->client->ping = floor((microtime(true) - $last) * 1000);
				break;
		}
	}
	
	/**
	 * @internal
	 *
	 * @param StreamHandler $stream
	 */
	public function onUpdate(StreamHandler $stream) {
		foreach ($this->toSend as $key => $item) {
			unset($this->toSend[$key]);
			$stream->write($item);
		}
	}

	/**
	 * Returns the client that was made during login procedure
	 *
	 * @api
	 *
	 * @return Client|null
	 */
	public function getClient(): ?Client {
		return $this->client;
	}

	/**
	 * Returns an IntentReceiveManager instance that handles all intents
	 *
	 * @internal
	 *
	 * @return IntentReceiveManager
	 */
	public function getIntentReceiveManager(): IntentReceiveManager {
		return $this->intentReceiveManager;
	}

	/**
	 * Returns the last instance made
	 *
	 * @api
	 *
	 * @return Discord|null
	 */
	public static function getInstance(): ?Discord {
		return self::$lastInstance;
	}
}