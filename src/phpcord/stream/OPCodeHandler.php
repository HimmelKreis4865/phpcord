<?php

namespace phpcord\stream;

use phpcord\Discord;
use function array_shift;
use function is_numeric;
use function json_encode;
use function microtime;
use function substr;
use function var_dump;

class OPCodeHandler {
	
	public function __call(string $name, array $parameters) {
		if (substr($name, 0, 2) !== "__") return;
		$name = substr($name, 2, strlen($name) - 2);
		/** @var WebSocket $ws */
		/** @var StreamLoop $loop */
		if (!is_numeric($name) or count($parameters) < 2 or !(($ws = array_shift($parameters)) instanceof WebSocket) or !(($loop = array_shift($parameters)) instanceof StreamLoop)) return;
		
		$data = array_shift($parameters);
		if (isset($data["s"])) Discord::getInstance()->lastSequence = $data["s"];
		
		switch (intval($name)) {
			case 0:
				Discord::getInstance()->getIntentReceiveManager()->executeIntent(Discord::getInstance(), $data["t"], $data["d"]);
				break;
			case 1:
				$ws->write(json_encode(["op" => 11]));
				break;
				
			case 7:
			case 9:
				$ws->invalidate();
				break;
			case 10:
				Discord::getInstance()->heartbeatInterval = $data["d"]["heartbeat_interval"];
				Discord::getInstance()->runHeartbeats();
				$loop->identify($ws);
				break;
				
			case 11:
				$loop->lastACK = microtime(true);
				break;
		}
	}
}