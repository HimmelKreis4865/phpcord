<?php

namespace phpcord\resource;

use function file_get_contents;
use function file_put_contents;
use function json_decode;
use function json_encode;

class JsonConfig extends Config {
	public function loadContent(string $path): void {
		$this->parsedContent = @json_decode(@file_get_contents($path), true) ?? [];
	}
	
	protected function saveFile(string $path) {
		file_put_contents($path, json_encode($this->parsedContent, JSON_PRETTY_PRINT));
	}
}