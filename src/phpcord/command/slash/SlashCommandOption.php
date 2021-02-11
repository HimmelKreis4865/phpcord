<?php

namespace phpcord\command\slash;

use function array_map;

class SlashCommandOption {
	
	public const SUB_COMMAND = 1;
	public const SUB_COMMAND_GROUP = 2;
	public const STRING = 3;
	public const INTEGER = 4;
	public const BOOLEAN = 5;
	public const USER = 6;
	public const CHANNEL = 7;
	public const ROLE = 8;
 
	/** @var string $name */
	protected $name;
	
	/** @var string $description */
	protected $description;
	
	/** @var SlashCommandOptionChoice[] $choices */
	protected $choices = [];
	
	/** @var SlashCommandOption[] $options */
	protected $options = [];
	
	/** @var int $type */
	protected $type = self::SUB_COMMAND;
	
	/** @var bool $required */
	protected $required = false;
	
	/**
	 * SlashCommandOption constructor.
	 *
	 * @param string $name
	 * @param string $description
	 * @param int $type
	 * @param bool $required
	 * @param SlashCommandOptionChoice[] $choices
	 * @param SlashCommandOption[] $options
	 */
	public function __construct(string $name, string $description, int $type = self::SUB_COMMAND, bool $required = false, array $choices = [], array $options = []) {
		$this->name = $name;
		$this->description = $description;
		$this->type = $type;
		$this->required	= $required;
		$this->choices = $choices;
		$this->options = $options;
	}
	
	public function encode(): array {
		return [
			"name" => $this->name,
			"description" => $this->description,
			"required" => $this->required,
			"type" => $this->type,
			"choices" => array_map(function(SlashCommandOptionChoice $choice) {
				return $choice->encode();
			}, $this->choices),
			"options" => array_map(function(SlashCommandOption $option) {
				return $option->encode();
			}, $this->options)
		];
	}
}