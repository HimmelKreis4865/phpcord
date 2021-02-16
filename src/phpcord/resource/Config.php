<?php

namespace phpcord\resource;

use function array_keys;
use function array_shift;
use function explode;
use function file_exists;
use function fopen;

abstract class Config {
	/** @var mixed $parsedContent */
	protected $parsedContent = [];
	
	/** @var string $path */
	protected $path;
	
	/**
	 * Config constructor.
	 *
	 * @param string $path the path to a target file
	 */
	public function __construct(string $path) {
		$this->path = $path;
		if (!file_exists($path)) @fopen($path, "w");
		$this->loadContent($path);
	}
	
	/**
	 * Returns a nested value of the config or $default if it doesn't exist
	 * Use . as split
	 *
	 * @api
	 *
	 * @param string $key
	 * @param null $default
	 *
	 * @return mixed
	 */
	public function getNested(string $key, $default = null) {
		$values = explode(".", $key);
		
		$array = $this->parsedContent;
		foreach ($values as $value) {
			if (isset($array[$value])) {
				$array = $array[$value];
			} else {
				return $default;
			}
		}
		return $array;
	}
	
	/**
	 * Set a nested entry to the content
	 * Use . as split
	 *
	 * @api
	 *
	 * @param string $key
	 *
	 * @param $value
	 */
	public function setNested(string $key, $value): void {
		$vars = explode(".", $key);
		$base = array_shift($vars);
		
		if(!isset($this->config[$base])){
			$this->parsedContent[$base] = [];
		}
		
		$base =& $this->parsedContent[$base];
		
		while(count($vars) > 0){
			$baseKey = array_shift($vars);
			if(!isset($base[$baseKey])){
				$base[$baseKey] = [];
			}
			$base =& $base[$baseKey];
		}
		
		$base = $value;
	}
	
	/**
	 * Returns the value of a key or null on failure (not found)
	 *
	 * @api
	 *
	 * @param string $key
	 * @param null $default
	 *
	 * @return mixed
	 */
	public function get(string $key, $default = null) {
		return $this->parsedContent[$key] ?? $default;
	}
	
	/**
	 * Sets value to a key
	 *
	 * @param string $key
	 *
	 * @param $value
	 */
	public function set(string $key, $value) {
		$this->parsedContent[$key] = $value;
	}
	
	/**
	 * Returns whether a config key exists or not
	 *
	 * @api
	 *
	 * @param string $key
	 *
	 * @return bool
	 */
	public function exists(string $key): bool {
		return isset($this->parsedContent[$key]);
	}
	
	/**
	 * Returns whether a nested config key exists or not
	 *
	 * @api
	 *
	 * @param string $key
	 *
	 * @return bool
	 */
	public function existsNested(string $key): bool {
		$vars = explode(".", $key);
		$array = $this->parsedContent;
		foreach ($vars as $var) {
			if (!isset($array[$var])) return false;
			if (!is_array($array[$var])) return true;
			$array = $array[$var];
		}
		return true;
	}
	
	/**
	 * Returns the whole file content (with cached values, so it might not be sync)
	 *
	 * @api
	 *
	 * @return array
	 */
	public function getAll(): array {
		return $this->parsedContent;
	}
	
	/**
	 * Returns all keys of first dimension @see getAll()
	 *
	 * @api
	 *
	 * @return array
	 */
	public function getAllKeys(): array {
		return array_keys($this->getAll());
	}
	
	/**
	 * Saves the file and stores the cache to the file
	 *
	 * @api
	 */
	public function save() {
		$this->saveFile($this->path);
	}
	
	/**
	 * Drops the whole cache and gets the file content again
	 *
	 * @warning Unsaved changes will disappear!
	 *
	 * @api
	 */
	public function reload() {
		$this->parsedContent = [];
		$this->loadContent($this->path);
	}
	
	/**
	 * Loads the file content from a file
	 *
	 * @internal
	 *
	 * @param string $path
	 */
	abstract protected function loadContent(string $path): void;
	
	/**
	 * Saves the content of @see Config::$parsedContent to the target path
	 *
	 * @internal
	 *
	 * @param string $path
	 *
	 * @return void
	 */
	abstract protected function saveFile(string $path);
}