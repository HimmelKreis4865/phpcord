<?php

namespace phpcord\command\slash;

final class SlashCommandManager {
	
	protected $application_id;
	
	public function __construct(string $application_id) {
		$this->application_id = $application_id;
	}
	
	public function getUrl(): string {
		return "https://discord.com/api/v8/applications/" . $this->application_id . "/commands";
	}
	
	public function registerCommand(SlashCommand $command) {
		$command = $command->encode();
		// todo: implement this correctly
	}
}