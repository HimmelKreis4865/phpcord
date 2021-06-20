<?php

namespace phpcord\task;

use phpcord\Discord;
use phpcord\utils\InstantiableTrait;
use ReflectionClass;
use ReflectionProperty;
use function get_class;

final class AsyncPool {
	use InstantiableTrait;
	
	/** @var AsyncTask[] $storage */
	protected $storage = [];
	
	public function submitTask(AsyncTask $task) {
		$this->storage[$task->getId()] = $task;
		$this->storage[$task->getId()]->start();
	}
	
	/**
	 * Workaround hack to modify values
	 *
	 * @internal
	 *
	 * @param AsyncTask $task
	 */
	public function updateTask(AsyncTask $task) {
		if (!$this->hasTask($task->getId())) return;
		$class = get_class($task);
		foreach (array_filter((new ReflectionClass($task))->getProperties(ReflectionProperty::IS_PUBLIC), function (ReflectionProperty $property) use ($class) {
			return $property->getDeclaringClass()->getName() === $class;
		}) as $property) {
			$this->storage[$task->getId()]->{$property->getName()} = $property->getValue($task);
		}
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