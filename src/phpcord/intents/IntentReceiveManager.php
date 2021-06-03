<?php

namespace phpcord\intents;

use phpcord\intents\handlers\BaseIntentHandler;
use phpcord\intents\handlers\ChannelHandler;
use phpcord\intents\handlers\GuildHandler;
use phpcord\intents\handlers\InteractionHandler;
use phpcord\intents\handlers\MemberHandler;
use phpcord\intents\handlers\MessageHandler;
use phpcord\intents\handlers\ReactionHandler;
use phpcord\Discord;
use Threaded;
use function var_dump;

class IntentReceiveManager extends Threaded {

	/** @var string[][] $intentHandlers */
	protected $intentHandlers = [];
	
	/** @var string[] */
	protected $registeredClasses = [];
	
	/**
	 * Registers a new intent handler to the system
	 *
	 * @warning Please don't register an anonymous class, it will throw an Exception!
	 *
	 * @internal
	 *
	 * @param BaseIntentHandler $intentHandler
	 *
	 * @return bool
	 */
	public function registerHandler(BaseIntentHandler $intentHandler): bool {
		foreach (array_filter($intentHandler->getIntents(), function($key) {
			return IntentsManager::isValidIntent($key);
		}) as $intent) {
			$list = $this->intentHandlers[$intent];
			$list[] = get_class($intentHandler);
			$this->intentHandlers[$intent] = $list;
		}
		return true;
	}
	
	/**
	 * Executes an intent, loops through all intent handlers of a type here
	 *
	 * @internal
	 *
	 * @param Discord $discord
	 * @param string $intent
	 * @param array $data
	 */
	final public function executeIntent(Discord $discord, string $intent, array $data) {
		if (!isset($this->intentHandlers[$intent])) return;
		foreach ($this->intentHandlers[$intent] as $class) {
			/** @var BaseIntentHandler $class */
			$class = new $class();
			$class->handle($discord, $intent, $data);
		}
	}
	
	/**
	 * Initialises all important default handlers
	 *
	 * @internal
	 */
	final public function init() {
		$this->initDefaultHandlers();
	}
	
	/**
	 * @see init()
	 *
	 * @internal
	 */
	private function initDefaultHandlers() {
		$this->registerHandler(new MessageHandler());
		$this->registerHandler(new MemberHandler());
		$this->registerHandler(new GuildHandler());
		$this->registerHandler(new ReactionHandler());
		$this->registerHandler(new ChannelHandler());
		$this->registerHandler(new InteractionHandler());
	}
}