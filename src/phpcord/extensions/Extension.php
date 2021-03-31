<?php

namespace phpcord\extensions;

use phpcord\Discord;
use phpcord\event\EventListener;
use ReflectionException;

abstract class Extension implements ExtensionBase {
	
	/** @var ExtensionInfo $info */
	protected $info;
	
	/**
	 * Extension constructor.
	 *
	 * @param ExtensionInfo $info
	 */
	final public function __construct(ExtensionInfo $info) {
		$this->info = $info;
	}
	
	/**
	 * Called when the bot logged in
	 *
	 * @api
	 */
	public function onEnable() {
	
	}
	
	/**
	 * Returns the info of the extensions
	 *
	 * @api
	 *
	 * @return ExtensionInfo
	 */
	public function getInfo(): ExtensionInfo {
		return $this->info;
	}
	
	/**
	 * Registers an EventListener instance
	 *
	 * @api
	 *
	 * @param EventListener $listener
	 *
	 * @throws ReflectionException
	 */
	public function subscribeEvents(EventListener $listener): void {
		Discord::getInstance()->registerEvents($listener);
	}
}