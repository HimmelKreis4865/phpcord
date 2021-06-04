<?php

namespace phpcord\input;

use phpcord\stream\ThreadConverter;
use phpcord\thread\Thread;
use function fopen;
use function fread;
use function var_dump;

class InputLoop extends Thread {
	
	public const INPUT_PREFIX = "CONSOLE::";
	
	protected $converter;
	
	public function __construct(ThreadConverter $converter) {
		$this->converter = $converter;
	}
	
	public function onRun() {
		$stream = fopen('php://stdin', 'r');
		while (true) {
			$in = trim(rtrim(fread($stream, 1024)));
			if ($in) {
				$this->converter->pushThreadToMain[] = self::INPUT_PREFIX . $in;
			}
		}
	}
}