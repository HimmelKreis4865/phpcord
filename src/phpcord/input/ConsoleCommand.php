<?php

namespace phpcord\input;

abstract class ConsoleCommand {
	
	/** @var string $name */
	protected $name;
	
	/** @var string $description */
	protected $description;
	
	/** @var array $aliases */
	protected $aliases;
	
	/**
	 * ConsoleCommand constructor.
	 *
	 * @param string $name
	 * @param string $description
	 * @param array $aliases
	 */
	public function __construct(string $name, string $description, array $aliases = []) {
		$this->name = $name;
		$this->description = $description;
		$this->aliases = $aliases;
	}
	
	/**
	 * Called when the command was typed in console
	 *
	 * @api
	 *
	 * @param array $args
	 */
	abstract public function execute(array $args);
	
	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
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
	public function getAliases(): array {
		return $this->aliases;
	}
}