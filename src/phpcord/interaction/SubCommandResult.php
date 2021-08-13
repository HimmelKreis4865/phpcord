<?php

namespace phpcord\interaction;

class SubCommandResult {
	
	public function __construct(protected int $type, protected string $name, protected mixed $value) { }
	
	/**
	 * @return int
	 */
	public function getType(): int {
		return $this->type;
	}
	
	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}
	
	/**
	 * @return mixed
	 */
	public function getValue(): mixed {
		return $this->value;
	}
}