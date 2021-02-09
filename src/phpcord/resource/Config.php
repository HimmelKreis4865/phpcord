<?php

namespace phpcord\resource;

use function array_keys;
use function array_shift;
use function explode;
use function file_exists;
use function fopen;
use function var_dump;

abstract class Config {
	/** @var array $parsedContent */
	protected $parsedContent = [];
	
	protected $path;
	
	public function __construct(string $path) {
		$this->path = $path;
		var_dump($path);
		if (!file_exists($path)) @fopen($path, "w");
		$this->loadContent($path);
	}
	
	/**
	 * @param string $key
	 * @param null $default
	 * @return mixed|null
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
	
	abstract public function loadContent(string $path): void;
	
	abstract protected function saveFile(string $path);
	
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
	
	public function get(string $key, $default = null) {
		return $this->parsedContent[$key] ?? $default;
	}
	
	public function set(string $key, $value) {
		$this->parsedContent[$key] = $value;
	}
	
	public function exists(string $key): bool {
		return isset($this->parsedContent[$key]);
	}
	
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
	
	public function getAll(): array {
		return $this->parsedContent;
	}
	
	public function getAllKeys(): array {
		return array_keys($this->parsedContent);
	}
	
	public function save() {
		$this->saveFile($this->path);
	}
	
	public function reload() {
		$this->parsedContent = [];
		$this->loadContent($this->path);
	}
}