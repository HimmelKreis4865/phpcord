<?php

namespace phpcord\command;

use phpcord\channel\BaseTextChannel;
use phpcord\guild\GuildMember;
use phpcord\guild\GuildMessage;

abstract class Command {

	public function __construct(protected string $name, protected int $type, protected string $description, protected array $subCommands = []) { }

	/**
	 * Returns the name of the command
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}
	
	/**
	 * @return int
	 */
	public function getType(): int {
		return $this->type;
	}
	
	/**
	 * @return string
	 */
	public function getDescription(): string {
		return $this->description;
	}
	
	/**
	 * @return array
	 */
	public function getOptions(): array {
		return $this->options;
	}
}