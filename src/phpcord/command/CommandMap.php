<?php

namespace phpcord\command;

use phpcord\channel\BaseTextChannel;
use phpcord\guild\GuildMember;
use phpcord\guild\GuildMessage;

abstract class CommandMap {
	/**
	 * Registers a command to the map if it doesn't already exist
	 *
	 * @api
	 *
	 * @param Command $command
	 */
	abstract public function register(Command $command);

	/**
	 * Returns a command if existing
	 *
	 * @api
	 *
	 * @param string $command
	 *
	 * @return Command|null
	 */
	abstract public function getCommand(string $command): ?Command;

	/**
	 * Unregisters a command if existing
	 *
	 * @api
	 *
	 * @param Command|string $command
	 */
	abstract public function unregister($command);

	/**
	 * Returns a list with all registered command
	 *
	 * @api
	 *
	 * @return Command[]
	 */
	abstract public function getAllCommands(): array;

	/**
	 * Returns a list of all Commands
	 *
	 * @api
	 *
	 * @return int
	 */
	abstract public function getCommandCount(): int;

	/**
	 * Adds a new prefix to the CommandMap, commands can now be run with {PREFIX}{COMMAND_NAME_OR_ALIAS}
	 *
	 * @api
	 *
	 * @param string $prefix
	 *
	 * @return bool
	 */
	abstract public function addPrefix(string $prefix): bool;

	/**
	 * Returns whether a message is a real command or not
	 *
	 * @api
	 *
	 * @param string $message
	 *
	 * @return bool
	 */
	abstract public function isValidCommand(string $message): bool;

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
	abstract public function executeCommand(BaseTextChannel $channel, GuildMessage $message): bool;
}