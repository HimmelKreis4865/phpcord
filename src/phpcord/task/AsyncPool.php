<?php

namespace phpcord\task;

use phpcord\utils\InstantiableTrait;
use Volatile;
use function spl_object_hash;

final class AsyncPool extends Volatile {
	
	use InstantiableTrait;
	
	public function submitTask(AsyncTask $task) {
		$this[$task->getId()] = spl_object_hash($task);
	}
	
	public function hasTask(int $id): bool {
		return isset($this[$id]);
	}
}