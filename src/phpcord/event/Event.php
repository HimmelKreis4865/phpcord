<?php

namespace phpcord\event;

use phpcord\exception\EventException;
use phpcord\Discord;
use phpcord\utils\ArrayUtils;

class Event {
	/** @var bool $cancelled */
	protected $cancelled = false;

	/**
	 * @api
	 *
	 * Cancels an Event if its Cancellable
	 *
	 * @throws EventException
	 */
	public function cancel() {
		if (!$this instanceof Cancellable) throw new EventException("An event that is not Cancellable cannot be cancelled!");
		$this->cancelled = true;
	}

	/**
	 * @api
	 *
	 * Returns whether an Event is cancelled or not
	 *
	 * @throws EventException
	 */
	public function isCancelled(): bool {
		if (!$this instanceof Cancellable) throw new EventException("An event that is not Cancellable cannot be cancelled!");
		return $this->cancelled;
	}

	/**
	 * Calls an event and executes code for listeners
	 *
	 * @api
	 */
	public function call() {
		if (!isset(Discord::getInstance()->listeners[static::class])) return;
		foreach (array_filter(ArrayUtils::asArray(Discord::getInstance()->listeners[static::class]), function($key) {
				return (isset($key[1]) and isset($key[0]) and ($key[0] instanceof EventListener));
		}) as $listener) {
			$listener[0]->{$listener[1]}($this);
		}
	}
}