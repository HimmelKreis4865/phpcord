<?php

namespace phpcord\command;

use phpcord\channel\BaseTextChannel;
use phpcord\guild\GuildMessage;
use phpcord\interaction\Interaction;

abstract class CommandMap {
	
	protected array $handlers = [];
	
	public function addHandler(string $name, callable $callable) {
		$this->handlers[$name] = $callable;
	}
	
	abstract public function executeHandler(Interaction $interaction): void;
}