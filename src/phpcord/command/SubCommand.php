<?php

namespace phpcord\command;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\ExpectedValues;
use function array_map;

class SubCommand {

	public const SUB_COMMAND = 1;
	
	public const SUB_COMMAND_GROUP = 2;
	
	public const STRING = 3;
	
	public const INTEGER = 4; // Any integer between -2^53 and 2^53
	
	public const BOOLEAN = 5;
	
	public const USER = 6;
	
	public const CHANNEL = 7; //Includes all channel types + categories
	
	public const ROLE = 8;
	
	public const MENTIONABLE = 9; // Includes users and roles
	
	public const NUMBER	= 10;
	
	/**
	 * SubCommand constructor.
	 *
	 * @param string $name
	 * @param int $type
	 * @param string $description
	 * @param bool $required
	 * @param string[]|int[]|float[] $choices [name1 => value1, name2 => value2]
	 * @param SubCommand[] $subCommands
	 */
	public function __construct(protected string $name, #[ExpectedValues(valuesFromClass: SubCommand::class)] protected int $type, protected string $description, protected bool $required = false, protected array $choices = [], protected array $subCommands = []) { }
	
	#[ArrayShape(["name" => "string", "type" => "int", "description" => "string", "required" => "bool", "choices" => "float[]|int[]|string[]", "options" => "array[]"])] public function encode(): array {
		return [
			"name" => $this->name,
			"type" => $this->type,
			"description" => $this->description,
			"required" => $this->required,
			"choices" => $this->getFormattedChoices(),
			"options" => array_map(function (SubCommand $subCommand): array { return $subCommand->encode(); }, $this->subCommands)
		];
	}
	
	final protected function getFormattedChoices(): array {
		$ar = [];
		foreach ($this->choices as $name => $value) $ar[] = ["name" => $name, "value" => $value];
		return $ar;
	}
	
	public static function fromArray(array $data): SubCommand {
		return new SubCommand($data["name"], $data["type"], $data["description"] ?? "", $data["required"] ?? false, self::choicesFromArray($data["choices"] ?? []), array_map(function (array $data): SubCommand { return SubCommand::fromArray($data); }, $data["options"] ?? []));
	}
	
	final protected static function choicesFromArray(array $data): array {
		$ar = [];
		foreach ($data as $choice) {
			$ar[$choice["name"]] = $choice["value"];
		}
		return $ar;
	}
}