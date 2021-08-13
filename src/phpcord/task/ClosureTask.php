<?php

namespace phpcord\task;

final class ClosureTask extends Task {
	
	public function __construct(protected $callable, int $delay = 0) {
		parent::__construct($delay);
	}
	
	public function onRun(int $tick): void {
		($this->callable)();
	}
}