<?php

namespace phpcord\resource;

use function file_put_contents;
use function yaml_emit;

class YamlConfig extends Config {
	/**
	 * Loads the file content from a file
	 *
	 * @internal
	 *
	 * @param string $path
	 */
	protected function loadContent(string $path): void {
		$this->parsedContent = @yaml_parse(@file_get_contents($path)) ?? [];
	}
	
	/**
	 * Saves the content of @see Config::$parsedContent to the target path
	 *
	 * @internal
	 *
	 * @param string $path
	 *
	 * @return void
	 */
	protected function saveFile(string $path) {
		 file_put_contents($path, @yaml_emit($this->parsedContent));
	}
}