<?php

namespace phpcord\task;

use phpcord\utils\MainLogger;
use function mt_rand;
use const PHP_INT_MAX;

abstract class Task {
	/** @var int $id */
	public $id;
	
	/** @var int $delay */
	private $delay;
	
	/** @var bool $repeating */
	private $repeating;
	
	/** @var int $lastRun */
	private $lastRun = -1;
	
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
	 * @param int $second
	 */
	final public function executeUpdate(int $second) {
		if (!$this->repeating and $this->lastRun !== -1) {
			$this->cancel();
			return;
		}
		if ($this->delay > 0) {
			$this->delay--;
			return;
		}
		if ($this->lastRun === -1 or ($second >= ($this->lastRun + $this->interval))) {
			$this->lastRun = $second;
			$this->onRun($second);
		}
	}
	
	/**
	 * Executes a sync update
	 *
	 * @api
	 *
	 * @param int $second
	 */
	abstract public function onRun(int $second): void;
}