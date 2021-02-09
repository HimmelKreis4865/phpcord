<?php

namespace phpcord\resource;

use function file_put_contents;
use function var_dump;
use function yaml_emit;

class YamlConfig extends Config {
	public function loadContent(string $path): void {
		var_dump($path);
		$this->parsedContent = @yaml_parse(@file_get_contents($path)) ?? [];
	}
	
	protected function saveFile(string $path) {
		 file_put_contents($path, @yaml_emit($this->parsedContent));
	}
}