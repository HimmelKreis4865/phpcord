<?php

namespace phpcord;

use BadMethodCallException;
use phpcord\command\CommandMap;
use phpcord\command\SimpleCommandMap;
use phpcord\client\Client;
use phpcord\event\Event;
use phpcord\event\EventListener;
use phpcord\exception\ClientException;
use phpcord\extensions\ExtensionManager;
use phpcord\guild\MessageSentPromise;
use phpcord\http\RestAPIHandler;
use phpcord\input\ConsoleCommandMap;
use phpcord\input\InputLoop;
use phpcord\intents\IntentReceiveManager;
use phpcord\stream\OPCodeHandler;
use phpcord\stream\StreamLoop;
use phpcord\stream\ThreadConverter;
use phpcord\task\AsyncPool;
use phpcord\task\defaults\HeartbeatTask;
use phpcord\task\TaskManager;
use phpcord\utils\LogStore;
use phpcord\utils\MainLogger;
use phpcord\utils\PermissionIds;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use function date;
use function file_exists;
use function ini_set;
use function is_dir;
use function is_subclass_of;
use function json_decode;
use function set_time_limit;
use function str_replace;
use function strlen;
use function substr;
use function usleep;
use function var_dump;
use const DIRECTORY_SEPARATOR;

final class Discord {
	/** @var int the version that is used for the gateway and restapi */
	public const VERSION = 8;

	/** @var null|Client $client */
	public $client;

	/** @var array $options */
	public $options = [];

	/** @var EventListener[][] $listeners */
	public $listeners = [];

	/** @var int $intents */
	public $intents = 513;

	/** @var MessageSentPromise[] $answerHandlers */
	public $answerHandlers = [];

	/** @var self|null $lastInstance */
	public static $lastInstance;
	
	/** @var bool $debugMode */
	public $debugMode = true;

	/** @var bool $loggedIn */
	private $loggedIn = false;
	
	/** @var IntentReceiveManager $intentReceiveManager */
	public $intentReceiveManager;
	
	/** @var null|CommandMap $commandMap */
	private $commandMap;
	
	public $sslSettings = [];
	
	/** @var int|null $lastSequence */
	public $lastSequence;
	
	/** @var int $heartbeatInterval The time we need to send the heartbeat in ms */
	public $heartbeatInterval;

	/** @var ConsoleCommandMap $consoleCommandMap */
	protected $consoleCommandMap;
	
	/** @var OPCodeHandler $opCodeHandler */
	protected $opCodeHandler;
	
	/** @var ThreadConverter $converter */
	protected $converter;
	
	/** @var string $token */
	public $token;
	
	/** @var null|int $heartbeatTask */
	protected $heartbeatTask;
	
	/** @var null | int $lastACK */
	public $lastACK = null;
	
	/** @var AsyncPool $asyncPool */
	protected $asyncPool;
	
	public function __construct(array $options = []) {
		set_time_limit(0);
		
		self::$lastInstance = $this;
		
		$this->registerAutoload();
		$this->registerErrorHandler();
		$this->registerShutdownHandler();
		$this->options = $options;
		
		$dir = __DIR__;
		LogStore::setLogFile(($dir[(strlen($dir) - 1)] === DIRECTORY_SEPARATOR ? $dir : $dir . DIRECTORY_SEPARATOR) . "save.log");
		LogStore::addMessage("\n\n" . "[STARTING PHPCORD AT " . date("d.m.Y H:i:s") . "]\n");
		$this->client = new Client();
		MainLogger::logInfo("Starting discord application...");
		PermissionIds::initDefinitions();
		
		if (isset($options["debugMode"]) and is_bool($options["debugMode"])) $this->debugMode = $options["debugMode"];
		
		if (isset($options["intents"]) and is_int($options["intents"])) $this->setIntents($options["intents"]);
	    $this->intentReceiveManager = new IntentReceiveManager();
	    
	    foreach ($options["extension_paths"] ?? [] as $path) {
	    	$this->registerExtensionPath($path);
		}
		
		$this->commandMap = new SimpleCommandMap();
	    $this->consoleCommandMap = new ConsoleCommandMap();
	    
		$this->opCodeHandler = new OPCodeHandler();
		$this->asyncPool = new AsyncPool();
	    
	    MainLogger::logInfo("Loading extensions...");
	    ExtensionManager::getInstance()->loadExtensions();
	    
		MainLogger::logInfo("§aLoading complete, waiting for a login now...");
		$this->initSSLSettings();
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
	 * Legacy method
	 *
	 * @deprecated
	 */
    public function enableCommandMap(): void {
		
	}
	
	/**
	 * @return AsyncPool
	 */
	public function getAsyncPool(): AsyncPool {
		return $this->asyncPool;
	}
	
	/**
	 * @return ConsoleCommandMap
	 */
	public function getConsoleCommandMap(): ConsoleCommandMap {
    	return $this->consoleCommandMap;
	}
	
	/**
	 * Returns the instance of the commandmap for discord commands
	 *
	 * @api
	 *
	 * @return CommandMap
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
		MainLogger::logInfo("Logging in...");
		if ($this->loggedIn) throw new ClientException("Could not connect to an already connected client!");

		if (is_null($token) and !isset($this->options["token"])) throw new BadMethodCallException("Couldn't login to Discord since there is no token specified");

		$this->token = $token = $token ?? $this->options["token"];

		$this->loggedIn = true;

		$this->intentReceiveManager->init();
		
		MainLogger::logInfo("Enabling extensions...");
		ExtensionManager::getInstance()->onEnable();
		
		MainLogger::logInfo("Authenticating REST API...");
		RestAPIHandler::getInstance()->setAuth($token);
		
		$this->converter = new ThreadConverter();
		
		$thread = new InputLoop($this->converter);
		$thread->start();
		
		MainLogger::logInfo("Starting websocket client...");
		
		$thread2 = new StreamLoop($this->converter);
		$thread2->start();
		$this->loop();
	}
	
	public function registerExtensionPath(string $path): void {
		if (!is_dir($path)) throw new InvalidArgumentException("Path $path does not exist!");
		ExtensionManager::getInstance()->registerExtensionPath($path);
	}

	public function loop(): void {
		while (true) {
			usleep(50 * 1000);
			TaskManager::getInstance()->onUpdate();
			$this->readThreads();
			$this->getAsyncPool()->tick();
		}
	}
	
	public function readThreads(): void {
		//var_dump($this->converter->pushThreadToMain);
		foreach ($this->converter->pushThreadToMain as $k => $message) {
			var_dump("got $message");
			$this->handleMessage($message);
			unset($this->converter->pushThreadToMain[$k]);
		}
	}
	
	public function pushToSocket(string $message) {
		var_dump("pushing $message");
		$this->converter->pushMainToThread[] = $message;
	}
	
	private function handleMessage(string $message): void {
		if (strlen($message) > InputLoop::INPUT_PREFIX and substr($message, 0, strlen(InputLoop::INPUT_PREFIX)) === InputLoop::INPUT_PREFIX) {
			// console command
			$this->getConsoleCommandMap()->executeCommand(substr($message, strlen(InputLoop::INPUT_PREFIX)));
			return;
		}
		// gateway message
		$data = json_decode($message, true);
		if (!$data) return;
		$this->opCodeHandler->{"__" . $data["op"]}($this, $data);
	}
	
	public function runHeartbeats(): void {
		if ($this->heartbeatTask !== null) TaskManager::getInstance()->getTask($this->heartbeatTask)->cancel();
		$task = new HeartbeatTask($this->heartbeatInterval);
		$this->heartbeatTask = $task->id;
		TaskManager::getInstance()->submitTask($task);
	}
	
	/**
	 * Registers Events on an EventListener subclass
	 *
	 * @api
	 *
	 * @param string|EventListener $eventListener
	 *
	 * @throws ReflectionException
	 */
	public function registerEvents($eventListener) {
		if (is_string($eventListener)) $eventListener = new $eventListener();
		if (!is_subclass_of($eventListener, EventListener::class)) return;
		$ref = new ReflectionClass($eventListener);
		foreach ($ref->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
			if ($method->isStatic() or $method->getNumberOfParameters() !== 1) continue;
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
	public function registerEvent(EventListener $listener, string $method_name, string $event) {
		if (!is_subclass_of($event, Event::class)) return;
		if (($ref = new ReflectionClass($listener))->isAnonymous())
			throw new InvalidArgumentException("Failed to register an anonymous EventListener class");
		$list = $this->listeners[$event] ?? [];
		$list[] = [$listener, $method_name];
		$this->listeners[$event] = $list;
	}
	
	/**
	 * Registers the autoload to remove includes
	 *
	 * @internal
	 */
	public static function registerAutoload() {
		spl_autoload_register(function($class) {
			$file = __DIR__ . DIRECTORY_SEPARATOR . str_replace(["\\", "\\\\", "/", "//"], DIRECTORY_SEPARATOR, str_replace("phpcord\\", "", $class)) . ".php";
			if (!class_exists($class) and file_exists($file)) require_once $file;
		});
	}
	
	private function initSSLSettings(): void {
		$this->sslSettings = [
			"verify_peer" => true,
			"verify_peer_name" => true,
			"cafile" => __DIR__ . DIRECTORY_SEPARATOR . "utils" . DIRECTORY_SEPARATOR . "cacert.pem",
			'ciphers' => 'HIGH:TLSv1.2:TLSv1.1:TLSv1.0:!SSLv3:!SSLv2',
		];
	}
	
	/**
	 * Returns whether the instance is already logged in or not
	 *
	 * @api
	 *
	 * @return bool
	 */
	public function isLoggedIn(): bool {
		return $this->loggedIn;
	}
	
	protected function registerErrorHandler() {
		// todo: implement this
	}
	
	protected function registerShutdownHandler() {
		// todo: implement this
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