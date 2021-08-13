<?php

namespace phpcord\stream;

use phpcord\Discord;
use phpcord\utils\PacketCreator;
use function array_shift;
use function json_encode;
use function microtime;
use function substr;
use function var_dump;

class OPCodeHandler {
	
	public function __call(string $name, array $parameters) {
		if (substr($name, 0, 2) !== "__") return;
		$name = substr($name, 2, strlen($name) - 2);
		
		/** @var array $data $data */
		$data = array_shift($parameters);
		
		if (isset($data["s"]) and is_numeric($data["s"])) Discord::getInstance()->lastSequence = $data["s"];
		
		switch (intval($name)) {
			case 0:
				Discord::getInstance()->getIntentReceiveManager()->executeIntent(Discord::getInstance(), $data["t"], $data["d"]);
				break;
			case 1:
				Discord::getInstance()->pushToSocket(json_encode(["op" => 11]));
				break;
				
			case 7:
			case 9:
				Discord::getInstance()->pushToSocket(StreamLoop::PREFIX_INVALIDATE);
				break;
			case 10:
				Discord::getInstance()->heartbeatInterval = $data["d"]["heartbeat_interval"];
				Discord::getInstance()->runHeartbeats();
				Discord::getInstance()->pushToSocket(PacketCreator::buildIdentify(Discord::getInstance()->token, Discord::getInstance()->intents));
				break;
				
			case 11:
				Discord::getInstance()->getClient()->ping = floor(($data["recv_time"] - Discord::getInstance()->lastHeartbeat) * 1000);
				var_dump("ping [" . (microtime(true) * 1000) . "|" . ($data["recv_time"] * 1000) . "] set to " . Discord::getInstance()->getClient()->getPing());
				break;
		}
	}
}