<?php

namespace phpcord\task;

use phpcord\Discord;
use phpcord\utils\InstantiableTrait;

final class AsyncPool {
	use InstantiableTrait;
	
	/** @var AsyncTask[] $storage */
	protected $storage = [];
	
	public function submitTask(AsyncTask $task) {
		$this->storage[$task->getId()] = $task;
		$this->storage[$task->getId()]->start();
	}
	
	public function hasTask(int $id): bool {
		return isset($this->storage[$id]);
	}
	
	public function tick(): void {
		foreach ($this->storage as $id => $task) {
			if ($task->isGarbage()) {
				$task->onCompletion(Discord::getInstance());
				unset($this->storage[$id]);
			}
		}
	}
}