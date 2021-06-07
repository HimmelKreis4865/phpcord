<?php

namespace phpcord\thread;

use phpcord\utils\ErrorHandler;
use function array_pop;
use function class_exists;
use function explode;
use function implode;
use function spl_autoload_register;
use function strlen;
use function substr;
use const DIRECTORY_SEPARATOR;

abstract class Thread extends \Thread {

	final public function run() {
		spl_autoload_register(function (string $class): void {
			if (substr($class, 0, strlen("phpcord\\")) === "phpcord\\") {
				if (!class_exists($class)) require str_replace("\\", DIRECTORY_SEPARATOR, $this->shiftDirectory(__DIR__) . DIRECTORY_SEPARATOR . substr($class, strlen("phpcord\\"), strlen($class))) . ".php";
			}
		});
		
		ErrorHandler::init();
		
		$this->onRun();
	}
	
	abstract public function onRun();
	
	private function shiftDirectory(string $dir): string {
		$folders = explode(DIRECTORY_SEPARATOR, $dir);
		array_pop($folders);
		
		return implode(DIRECTORY_SEPARATOR, $folders);
	}
}