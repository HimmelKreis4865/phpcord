<?php

namespace phpcord\task\defaults;

use phpcord\Discord;
use phpcord\task\Task;
use function var_dump;

class HeartbeatTask extends Task {
	
	public function __construct(int $heartbeatInterval) {
		parent::__construct((($heartbeatInterval - 500) / 50), true, (($heartbeatInterval - 500) / 50));
	}
	
	public function onRun(int $tick): void {
		var_dump("seq: " . Discord::getInstance()->lastSequence);
	}
}