<?php

namespace phpcord\command;

use phpcord\channel\BaseTextChannel;
use phpcord\guild\GuildMessage;
use phpcord\utils\ArrayUtils;
use InvalidArgumentException;
use function array_keys;
use function array_merge;
use function array_shift;
use function count;
use function explode;
use function str_replace;
use function substr;

class SimpleCommandMap extends CommandMap {
	/** @var Command[] $commands */
	protected $commands = [];
	
	/** @var string[] $prefixes */
	protected $prefixes = [];

	/**
	 * Registers a command to the map if it doesn't already exist
	 *
	 * @api
	 *
	 * @param Command $command
	 * @param bool $override
	 */
	public function register(Command $command, bool $override = false) {
		$names = array_merge([$command->getName()], $command->getAliases());
		foreach ($names as $name) {
			if (strlen($name) === 0) continue;
			if (!$override and isset($this->commands[$name])) throw new InvalidArgumentException("Command or alias " . $name . " already exists and cannot be assigned twice!");
			$this->commands[$name] = $command;
		}
	}

	/**
	 * Returns a command if existing
	 *
	 * @api
	 *
	 * @param string $command
	 *
	 * @return Command|null
	 */
	public function getCommand(string $command): ?Command {
		return @$this->commands[$command];
	}

	/**
	 * Unregisters a command if existing
	 *
	 * @api
	 *
	 * @param Command|string $command
	 */
	public function unregister($command) {
		if (!($command instanceof Command)) {
			$name = $command;
			if (($command = $this->getCommand($command)) === null) throw new InvalidArgumentException("Command or alias $name is not loaded!");
		}
		$names = array_merge([$command->getName()], $command->getAliases());
		foreach ($names as $name) {
			unset($this->commands[$name]);
		}
	}

	/**
	 * Returns a list with all registered command
	 *
	 * @api
	 *
	 * @return Command[]
	 */
	public function getAllCommands(): array {
		return $this->commands;
	}
	/**
	 * Returns a list of all Commands
	 *
	 * @api
	 *
	 * @return int
	 */
	public function getCommandCount(): int {
		return count($this->getAllCommands());
	}

	/**
	 * Adds a new prefix to the CommandMap, commands can now be run with {PREFIX}{COMMAND_NAME_OR_ALIAS}
	 *
	 * @api
	 *
	 * @param string $prefix
	 *
	 * @return bool
	 */
	public function addPrefix(string $prefix): bool {
		if (strlen($prefix) === 0) return false;
		if (in_array($prefix, $this->prefixes)) return false;
		$this->prefixes[] = $prefix;
		return true;
	}

	/**
	 * Returns whether a message is a real command or not
	 *
	 * @api
	 *
	 * @param string $message
	 *
	 * @return bool
	 */
	public function isValidCommand(string $message): bool {
		foreach ($this->prefixes as $prefix) {
			foreach (array_keys($this->commands) as $command) {
				$target_command = $prefix . $command;
				if (substr($message, 0, strlen($target_command)) === $target_command) return true;
			}
		}
		return false;
	}

	/**
	 * Command validation and execution if valid
	 *
	 * @api
	 *
	 * @param BaseTextChannel $channel
	 * @param GuildMessage $message
	 *
	 * @return bool
	 */
	public function executeCommand(BaseTextChannel $channel, GuildMessage $message): bool {
		if (!$this->isValidCommand($message->getContent())) return false;

		$args = explode(" ", $message->getContent());
		ArrayUtils::convertStringArray($args);
		$command = str_replace($this->prefixes, "", array_shift($args));

		if (($command = $this->getCommand($command)) === null) return false;
		if (!$command->canUse($message->getMember())) return false;

		$command->execute($channel, $message, $args);
		return true;
	}
}


