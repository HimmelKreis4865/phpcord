<?php

namespace phpcord\command\slash;

class ApplicationCommandInteractionData {
	/** @var string $name */
	public $name;
	
	/** @var string $id */
	public $id;
	
	/** @var ApplicationCommandInteractionDataOption[] */
	public $options = [];
	
	/**
	 * ApplicationCommandInteractionDataOption constructor.
	 * @param string $name
	 * @param string $id
	 * @param ApplicationCommandInteractionDataOption[] $options
	 */
	public function __construct(string $name, string $id, $options = []) {
		$this->name = $name;
		$this->id = $id;
		$this->options = $options;
	}
}