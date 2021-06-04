<?php

namespace phpcord\task\defaults;

use phpcord\Discord;
use phpcord\task\Task;
use phpcord\utils\MainLogger;
use phpcord\utils\PacketCreator;
use function floor;
use function var_dump;

class HeartbeatTask extends Task {
	
	public function __construct(int $heartbeatInterval) {
		var_dump("task enabled $heartbeatInterval");
		parent::__construct(floor((($heartbeatInterval - 500) / 50)), true, floor((($heartbeatInterval - 500) / 50)));
	}
	
	public function onRun(int $tick): void {
		MainLogger::logInfo("Heartbeating now");
		Discord::getInstance()->pushToSocket(PacketCreator::buildHeartbeat(Discord::getInstance()->lastSequence));
	}
}