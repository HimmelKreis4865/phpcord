<?php

namespace phpcord\command;

use phpcord\interaction\Interaction;

final class GuildCommandMap extends CommandMap {
	
	public function __construct(protected string $guildId) { }
	
	public function executeHandler(Interaction $interaction): void {
		if (isset($this->handlers[$interaction->getData()->getName()])) ($this->handlers[$interaction->getData()->getName()])($interaction);
	}
	
	/**
	 * @return string
	 */
	public function getGuildId(): string {
		return $this->guildId;
	}
}