<?php

namespace phpcord\resource;

use function file_get_contents;
use function file_put_contents;
use function json_decode;
use function json_encode;

class JsonConfig extends Config {
	/**
	 * Loads the file content from a file
	 *
	 * @internal
	 *
	 * @param string $path
	 */
	protected function loadContent(string $path): void {
		$this->parsedContent = @json_decode(@file_get_contents($path), true) ?? [];
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
		file_put_contents($path, json_encode($this->parsedContent, JSON_PRETTY_PRINT));
	}
}