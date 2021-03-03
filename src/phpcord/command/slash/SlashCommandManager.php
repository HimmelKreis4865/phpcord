<?php

namespace phpcord\command\slash;

use phpcord\http\RestAPIHandler;

final class SlashCommandManager {
	/** @var string $application_id */
	protected $application_id;
	
	/** @var SlashCommand[] $commands */
	protected $commands = [];
	
	/**
	 * SlashCommandManager constructor.
	 *
	 * @param string $application_id
	 */
	public function __construct(string $application_id) {
		$this->application_id = $application_id;
	}
	
	/**
	 * @return string
	 */
	public function getUrl(): string {
		return "https://discord.com/api/v8/applications/" . $this->application_id . "/commands";
	}
	
	public function registerCommand(SlashCommand $command) {
		$this->commands[$command->getName()] = $command;
		if ($command->getGuildId() === null) {
			RestAPIHandler::getInstance()->registerGlobalSlashCommand($this->application_id, $command->encode());
		} else {
			RestAPIHandler::getInstance()->registerSlashCommand($command->getGuildId(), $this->application_id, $command->encode());
		}
	}
}