<?php

namespace phpcord\task;

use phpcord\utils\MainLogger;
use function mt_rand;
use function var_dump;
use const PHP_INT_MAX;

abstract class Task {
	/** @var int $id */
	public $id;
	
	/** @var int $delay */
	private $delay;
	
	/** @var bool $repeating */
	private $repeating;
	
	/** @var int $lastRun */
	private $lastRun = 0;
	
	/** @var bool $cancelled */
	private $cancelled = false;
	
	/** @var int $interval */
	private $interval;
	
	/**
	 * Task constructor.
	 *
	 * @param int $delay
	 * @param bool $repeating
	 * @param int $interval
	 */
	public function __construct(int $delay = 0, bool $repeating = false, int $interval = 1) {
		var_dump("ticks: $delay | $interval");
		do {
			$this->id = mt_rand(PHP_INT_MIN, PHP_INT_MAX);
		} while (TaskManager::getInstance()->getTask($this->id) instanceof Task);
		
		$this->delay = $delay;
		$this->repeating = $repeating;
		$this->interval = $interval;
	}
	
	/**
	 * Cancels a the current task
	 *
	 * @api
	 */
	final public function cancel() {
		if ($this->isCancelled()) return;
		MainLogger::logDebug("Cancelled task {$this->id}");
		TaskManager::getInstance()->removeTask($this);
	}
	
	/**
	 * Returns whether a task is cancelled or not
	 *
	 * @api
	 *
	 * @return bool
	 */
	final public function isCancelled(): bool {
		return $this->cancelled;
	}
	
	/**
	 * Executes an update and validates it
	 *
	 * @internal
	 *
	 * @param int $tick
	 */
	final public function executeUpdate(int $tick) {
		if ($this->delay > 0) {
			$this->delay--;
			return;
		}
		
		if ($tick >= ($this->lastRun + $this->interval)) {
			$this->lastRun = $tick;
			$this->onRun($tick);
			if (!$this->repeating) $this->cancel();
		}
	}
	
	/**
	 * Executes a sync update
	 *
	 * @api
	 *
	 * @param int $tick
	 */
	abstract public function onRun(int $tick): void;
}