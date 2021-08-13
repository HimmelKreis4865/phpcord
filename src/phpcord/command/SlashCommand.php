<?php

namespace phpcord\command;

use BadMethodCallException;
use InvalidArgumentException;
use JetBrains\PhpStorm\ExpectedValues;
use phpcord\Discord;
use phpcord\http\RestAPIHandler;
use phpcord\task\Promise;
use RuntimeException;
use function array_map;
use function preg_match;
use function var_dump;

class SlashCommand extends Command {

	public const DEFAULT = 1;
	
	public const USER = 2;
	
	public const MESSAGE = 3;
	
	public function __construct(string $name, #[ExpectedValues(valuesFromClass: SlashCommand::class)] int $type, string $description, array $subCommands = [], protected ?string $id = null, protected ?string $guildId = null) {
		if (!preg_match("/^[\w-]{1,32}$/", $name)) throw new InvalidArgumentException("Invalid name \"$name\" given! A name can only have alphanumeric characters with a maxium length of 32!");
		parent::__construct($name, $type, $description, $subCommands);
	}
	
	public function encode(array $add = []): array {
		return ([
			"name" => $this->name,
			"type" => $this->type,
			"description" => $this->description,
			"options" => array_map(function (SubCommand $subCommand): array { return $subCommand->encode(); }, $this->subCommands)
		] + $add);
	}
	
	public function delete(): Promise {
		if ($this->getId() === null) throw new BadMethodCallException("Cannot delete a slashcommand that wasn't fetched!");
		if ($this->guildId !== null) {
			return RestAPIHandler::getInstance()->removeSlashCommand($this->getId(), Discord::getInstance()->getApplicationId(), $this->guildId);
		}
		// todo: return RestAPIHandler::getInstance()->removeGlobalSlashCommand($this->getId(), Discord::getInstance()->getApplicationId());
		throw new RuntimeException("This operation is currently unsupported!");
	}
	
	/**
	 * @return string|null
	 */
	public function getId(): ?string {
		return $this->id;
	}
	
	public static function fromArray(array $data, ?string $guildId = null): SlashCommand {
		return new SlashCommand($data["name"], $data["type"], $data["description"], array_map(function (array $data): SubCommand { return SubCommand::fromArray($data); }, $data["options"] ?? []), $data["id"], $guildId);
	}
}