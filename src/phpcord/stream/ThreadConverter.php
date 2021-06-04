<?php

namespace phpcord\stream;

use Threaded;

class ThreadConverter extends Threaded {
	
	public $pushMainToThread = [];
	
	public $pushThreadToMain = [];
	
	public $running = true;
}