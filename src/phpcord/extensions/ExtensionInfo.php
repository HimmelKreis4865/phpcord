<?php

namespace phpcord\extensions;

use InvalidArgumentException;
use function property_exists;
use function strtolower;

class ExtensionInfo {
	/** @var string $name */
	protected $name;
	
	/** @var string $main */
	protected $main;
	
	/** @var string $author */
	protected $author;
	
	/** @var string $version */
	protected $version;
	
	/** @var string $website */
	protected $website;
	
	/** @var string[] $dependencies */
	protected $dependencies = [];
	
	public static function fromData(array $data): self {
		if (!isset($data["main"]) or !isset($data["name"]) or !isset($data["version"])) throw new InvalidArgumentException("Invalid ext.yml format found: A basic structure needs at least version, main & name");
		
		$info = new ExtensionInfo();
		$info->init($data);
		
		return $info;
	}
	
	private function init(array $data): void {
		foreach ($data as $key => $value) {
			if (property_exists($this, strtolower($key))) $this->{strtolower($key)} = $value;
		}
	}
	
	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}
	
	/**
	 * @return string|null
	 */
	public function getAuthor(): ?string {
		return $this->author;
	}
	
	/**
	 * @return string[]
	 */
	public function getDependencies(): array {
		return $this->dependencies;
	}
	
	/**
	 * @return string
	 */
	public function getMain(): string {
		return $this->main;
	}
	
	/**
	 * @return string|null
	 */
	public function getVersion(): ?string {
		return $this->version;
	}
	
	/**
	 * @return string|null
	 */
	public function getWebsite(): ?string {
		return $this->website;
	}
}