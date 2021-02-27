<?php

namespace phpcord\task;

use phpcord\utils\InstantiableTrait;

final class TaskManager {
	use InstantiableTrait;
	
	/** @var Task[] $tasks */
	protected $tasks = [];
	
	/** @var int $tick */
	protected $tick = 0;
	
	/**
	 * Submits a new Task to the manager
	 *
	 * @api
	 *
	 * @param Task $task
	 */
	public function submitTask(Task $task) {
		$this->tasks[$task->id] = $task;
	}
	
	/**
	 * Returns the task by an ID or null
	 *
	 * @api
	 *
	 * @param int $id
	 *
	 * @return Task|null
	 */
	public function getTask(int $id): ?Task {
		return @$this->tasks[$id];
	}
	
	/**
	 * Removes a Task and stops it, @see Task::cancel()
	 *
	 * @internal
	 *
	 * @param $task
	 */
	public function removeTask($task) {
		if ($task instanceof Task) $task = $task->id;
		if (isset($this->tasks[$task])) unset($this->tasks[$task]);
	}
	
	/**
	 * Returns an array with all tasks registered
	 *
	 * @api
	 *
	 * @return Task[]
	 */
	public function getTasks(): array {
		return $this->tasks;
	}
	
	/**
	 * Called on update ~ every second
	 *
	 * @internal
	 */
	public function onUpdate() {
		$this->tick++;
		
		foreach ($this->tasks as $id => $task) {
			if ($task->isCancelled()) {
				unset($this->tasks[$id]);
				continue;
			}
			$task->executeUpdate($this->tick);
		}
	}
}