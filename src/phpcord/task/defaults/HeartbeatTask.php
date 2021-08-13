<?php

namespace phpcord\task\defaults;

use phpcord\Discord;
use phpcord\task\Task;
use phpcord\utils\PacketCreator;
use function floor;
use function microtime;
use function mt_rand;

class HeartbeatTask extends Task {
	
	public function __construct(int $heartbeatInterval) {
		parent::__construct(20, true, floor((($heartbeatInterval - 500) / 50)));
	}
	
	public function onRun(int $tick): void {
		Discord::getInstance()->lastHeartbeat = microtime(true) + (mt_rand(1, 40) / 1000);
		Discord::getInstance()->pushToSocket(PacketCreator::buildHeartbeat(Discord::getInstance()->lastSequence));
	}
}