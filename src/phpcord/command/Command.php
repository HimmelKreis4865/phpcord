<?php

namespace phpcord\command;

use phpcord\channel\BaseTextChannel;
use phpcord\guild\GuildMember;
use phpcord\guild\GuildMessage;

abstract class Command {
	/** @var string $name */
	protected $name;

	/** @var array $aliases */
	protected $aliases = [];

	/**
	 * Command constructor.
	 *
	 * @param string $name
	 * @param array $aliases
	 */
	public function __construct(string $name, array $aliases = []) {
		$this->name = $name;
		$this->aliases = $aliases;
	}

	/**
	 * Returns whether a member can use the command or not
	 *
	 * For no permission check needed, just don't modify it in your own command
	 *
	 * @api
	 *
	 * @param GuildMember $member
	 *
	 * @return bool
	 */
	public function canUse(GuildMember $member): bool {
		return true;
	}

	/**
	 * Body of command execution
	 *
	 * @api / @internal  use it as api but don't call it unless you know what you're doing
	 *
	 * @param BaseTextChannel $channel
	 * @param GuildMessage $message
	 * @param array $args
	 *
	 * @return void
	 */
	abstract public function execute(BaseTextChannel $channel, GuildMessage $message, array $args): void;


	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @return array
	 */
	public function getAliases(): array {
		return $this->aliases;
	}
}


