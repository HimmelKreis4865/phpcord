<?php

namespace phpcord\guild;

final class GuildWelcomeScreen {
	/** @var string $description */
	protected $description;
	
	/** @var GuildWelcomeScreenField[] $fields */
	protected $fields = [];
	
	/**
	 * GuildWelcomeScreen constructor.
	 * @param string $description
	 * @param GuildWelcomeScreenField[] $fields
	 */
	public function __construct(string $description, array $fields = []) {
		$this->description = $description;
		$this->fields = $fields;
	}
	
	/**
	 * @return string
	 */
	public function getDescription(): string {
		return $this->description;
	}
	
	/**
	 * @return GuildWelcomeScreenField[]
	 */
	public function getFields(): array {
		return $this->fields;
	}
}