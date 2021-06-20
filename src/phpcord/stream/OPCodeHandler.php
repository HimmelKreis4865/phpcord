<?php

namespace phpcord\stream;

use phpcord\Discord;
use phpcord\utils\PacketCreator;
use function array_shift;
use function json_encode;
use function microtime;
use function substr;

class OPCodeHandler {
	
	public function __call(string $name, array $parameters) {
		if (substr($name, 0, 2) !== "__") return;
		$name = substr($name, 2, strlen($name) - 2);
		
		/** @var Discord $discord */
		$discord = array_shift($parameters);
		/** @var array $data $data */
		$data = array_shift($parameters);
		
		if (isset($data["s"])) Discord::getInstance()->lastSequence = $data["s"];
		
		switch (intval($name)) {
			case 0:
				$discord->getIntentReceiveManager()->executeIntent($discord, $data["t"], $data["d"]);
				break;
			case 1:
				$discord->pushToSocket(json_encode(["op" => 11]));
				break;
				
			case 7:
			case 9:
				$discord->pushToSocket(StreamLoop::PREFIX_INVALIDATE);
				break;
			case 10:
				$discord->heartbeatInterval = $data["d"]["heartbeat_interval"];
				$discord->runHeartbeats();
				$discord->pushToSocket(PacketCreator::buildIdentify($discord->token, $discord->intents));
				break;
				
			case 11:
				Discord::getInstance()->getClient()->ping = floor((microtime(true) - Discord::getInstance()->lastHeartbeat) * 1000);
				break;
		}
	}
}