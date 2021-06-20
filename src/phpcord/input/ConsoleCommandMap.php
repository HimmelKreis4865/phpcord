<?php

namespace phpcord\input;


use phpcord\utils\ArrayUtils;
use function array_merge;
use function array_shift;
use function explode;
use function is_string;
use function strtolower;

final class ConsoleCommandMap {
	
	/** @var ConsoleCommand[] $commands */
	protected $commands = [];
	
	public function register(ConsoleCommand $command) {
		foreach (array_merge([$command->getName()], $command->getAliases()) as $name) {
			$this->commands[strtolower($name)] = $command;
		}
	}
	
	public function getCommand(string $command): ?ConsoleCommand {
		return @$this->commands[$command];
	}
	
	public function unregister($command) {
		if (is_string($command)) $command = $this->getCommand($command);
		if ($command === null) return;
		
		foreach (array_merge([$command->getName()], $command->getAliases()) as $name) {
			if (isset($this->commands[strtolower($name)])) unset($this->commands[strtolower($name)]);
		}
	}
	
	public function getAllCommands(): array {
		return ArrayUtils::asArray($this->commands);
	}
	
	public function getCommandCount(): int {
		return count($this->commands);
	}
	
	public function isValidCommand(string $message): bool {
		return (bool) $this->getCommand(strtolower(explode(" ", $message)[0]));
	}
	
	public function executeCommand(string $message): bool {
		if (!$this->isValidCommand($message)) return false;
		
		$args = explode(" ", $message);
		$command = $this->getCommand(strtolower(array_shift($args)));
		$command->execute($args);
		return true;
	}
}