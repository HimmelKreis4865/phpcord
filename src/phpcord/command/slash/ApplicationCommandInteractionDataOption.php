<?php

namespace phpcord\command\slash;

class ApplicationCommandInteractionDataOption {
	/** @var string $name */
	public $name;
	
	/** @var mixed $value */
	public $value;
	
	/** @var ApplicationCommandInteractionDataOption[] */
	public $options = [];
	
	/**
	 * ApplicationCommandInteractionDataOption constructor.
	 * @param string $name
	 * @param null $value
	 * @param ApplicationCommandInteractionDataOption[] $options
	 */
	public function __construct(string $name, $value = null, $options = []) {
		$this->name = $name;
		$this->value = $value;
		$this->options = $options;
	}
}