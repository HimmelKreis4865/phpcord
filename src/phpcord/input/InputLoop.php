<?php

namespace phpcord\input;

use phpcord\thread\Thread;
use function fopen;
use function fread;
use function var_dump;

class InputLoop extends Thread {
	
	protected $map;
	
	public function __construct(ConsoleCommandMap $map) {
		$this->map = $map;
	}
	
	public function onRun() {
		$stream = fopen('php://stdin', 'r');
		while (true) {
			$in = trim(rtrim(fread($stream, 1024)));
			if ($in) {
				$this->map->executeCommand($in);
			}
		}
	}
}