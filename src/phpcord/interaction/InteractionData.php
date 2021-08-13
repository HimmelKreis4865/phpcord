<?php

namespace phpcord\interaction;

use phpcord\utils\SingletonTrait;

class InteractionData {
	use SingletonTrait;
	
	/** @var int $type */
	public int $type;
	
	/** @var string $id */
	public string $id;
	
	/** @var string $name */
	protected string $name;
	
	/** @var ResolvedDetails $details */
	protected ResolvedDetails $details;
	
	/** @var SubCommandResult[] $options */
	protected array $options;
	
	public static function fromArray(string $guildId, array $data): InteractionData {
		$instance = new InteractionData();
		$instance->name = $data["name"];
		$instance->type = $data["type"];
		$instance->id = $data["id"];
		$instance->details = ResolvedDetails::fromArray($guildId, $data["resolved"] ?? []);
		$instance->options = $instance->details->parseOptions($data["options"] ?? []);
		return $instance;
	}
	
	public function getSubCommand(string $name): ?SubCommandResult {
		return @$this->options[$name];
	}
	
	public function getSubCommands(): array {
		return $this->options;
	}
	
	/**
	 * @return ResolvedDetails
	 */
	public function getDetails(): ResolvedDetails {
		return $this->details;
	}
	
	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}
	
	/**
	 * @return SubCommandResult[]
	 */
	public function getOptions(): array {
		return $this->options;
	}
}