<?php

namespace phpcord\client;

use phpcord\Discord;
use phpcord\event\system\TokenRequestEvent;
use phpcord\exception\EventException;
use phpcord\utils\InstantiableTrait;

class MessageHandler extends Handler {
	use InstantiableTrait;

	public function handle(Discord $discord, string $message) {
		$message = json_decode($message, true);
		var_dump($message);
		switch (intval($message["op"])) {
			case 10:
				$interval = $message["d"]["heartbeat_interval"];

				// creating a loop to send heartbeats
				$loop = \React\EventLoop\Factory::create();
				$loop->addPeriodicTimer(($interval / 1000), function () use ($discord) {
					$discord->send(json_encode(["op" => 1, "d" => null]));
				});

				// sending an identity pk
				$event = new TokenRequestEvent();
				$event->call();

				if ($event->getToken() === null){
					$discord->conn->close();
					throw new \InvalidArgumentException("Can't identify without secret token!");
				}
				var_dump($discord->conn->send(file_get_contents(str_replace(";;", $event->getToken(), __DIR__ . "\identity.json"))));
				break;
		}
	}
}


