<?php

/*
 *         .__                                       .___
 * ______  |  |__  ______    ____   ____ _______   __| _/
 * \____ \ |  |  \ \____ \ _/ ___\ /  _ \\_  __ \ / __ |
 * |  |_> >|   Y  \|  |_> >\  \___(  <_> )|  | \// /_/ |
 * |   __/ |___|  /|   __/  \___  >\____/ |__|   \____ |
 * |__|         \/ |__|         \/                    \/
 *
 *
 * This library is developed by HimmelKreis4865 © 2022
 *
 * https://github.com/HimmelKreis4865/phpcord
 */

namespace phpcord;

use Closure;
use phpcord\async\AsyncPool;
use phpcord\autoload\DynamicAutoloader;
use phpcord\cache\DefaultMappingsCache;
use phpcord\event\EventRegistry;
use phpcord\exception\EventException;
use phpcord\intent\Intents;
use phpcord\logger\Logger;
use phpcord\runtime\network\Network;
use phpcord\runtime\tick\Ticker;
use phpcord\scheduler\Scheduler;
use phpcord\utils\Collection;
use phpcord\utils\error\ErrorHook;
use phpcord\voice\VoiceConnectionPool;
use RuntimeException;
use function define;
use function getcwd;
use function is_object;
use function realpath;
use const DIRECTORY_SEPARATOR;
use const SRC_PATH;

final class Discord {
	
	/**
	 * This is custom implemented to prevent load mistakes
	 * @var Discord
	 */
	private static Discord $instance;
	
	/** @var Logger $logger */
	private Logger $logger;
	
	/** @var DynamicAutoloader $autoloader */
	private DynamicAutoloader $autoloader;
	
	/** @var string $token */
	private string $token;
	
	/** @var int $intents */
	private int $intents;
	
	/**
	 * The client will be initialized once ready intent was received
	 * @var Client|null $client
	 */
	private ?Client $client = null;
	
	public function __construct() {
		define('SRC_PATH', __DIR__ . DIRECTORY_SEPARATOR);
		define('DATA_PATH', realpath(getcwd()) . DIRECTORY_SEPARATOR);
		define('RESOURCE_PATH', SRC_PATH . 'resources' . DIRECTORY_SEPARATOR);
		
		require 'autoload/Autoloadable.php';
		require 'autoload/DynamicAutoloader.php';
		$this->autoloader = new DynamicAutoloader();
		$this->autoloader->load('phpcord\\', SRC_PATH);
		$this->autoloader->load('', DATA_PATH);
		
		self::$instance = $this;
		
		$this->logger = new Logger();
		
		ErrorHook::getInstance()->initiate();
		
		Ticker::getInstance()->register(Network::getInstance());
		Ticker::getInstance()->register(AsyncPool::getInstance());
		Ticker::getInstance()->register(Scheduler::getInstance());
		Ticker::getInstance()->register(VoiceConnectionPool::getInstance());
	}
	
	/**
	 * This is custom implemented to prevent load mistakes
	 *
	 * @return Discord
	 */
	public static function getInstance(): Discord {
		return self::$instance;
	}
	
	/**
	 * @return Client|null
	 */
	public function getClient(): ?Client {
		return $this->client;
	}
	
	public function setDebugging(bool $enabled): void {
		$this->logger->setDebugging($enabled);
	}
	
	/**
	 * @internal
	 *
	 * @param Client $client
	 *
	 * @return void
	 */
	public function __setClient(Client $client): void {
		$this->client = $client;
	}
	
	/**
	 * @return Logger
	 */
	public function getLogger(): Logger {
		return $this->logger;
	}
	
	/**
	 * May not be called before @see Discord::login()
	 *
	 * @return string
	 */
	public function getToken(): string {
		return $this->token;
	}
	
	/**
	 * May not be called before @see Discord::login()
	 *
	 * @return int
	 */
	public function getIntents(): int {
		return $this->intents;
	}
	
	public function listen(string|object $event_class_or_listener_object, Closure $closure = null): void {
		if (is_object($event_class_or_listener_object)) {
			EventRegistry::getInstance()->registerListenerObject($event_class_or_listener_object);
			return;
		}
		if (!$closure) throw new EventException('A listener for one certain event requires a valid Closure, null given');
		EventRegistry::getInstance()->registerListener($event_class_or_listener_object, $closure);
	}
	
	/**
	 * @param string $token
	 * @param int|null $customIntents
	 * @see Intents
	 *
	 * @return void
	 */
	public function login(string $token, int $customIntents = null): void {
		$this->token = $token;
		$this->intents = $customIntents ?? Intents::recommendedIntents();
		Network::getInstance()->init();
		$this->getLogger()->info('Startup succeed.');
		Ticker::getInstance()->start();
	}
}