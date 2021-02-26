<?php

use phpcord\Discord;
use phpcord\event\EventListener;
use phpcord\event\message\MessageSendEvent;
use phpcord\intents\IntentsManager;

require_once __DIR__ . "/src/phpcord/Discord.php";

$discord = new Discord([
	"debugMode" => true,
	"ssl" => [
		"verify_peer" => true,
		"verify_peer_name" => true,
		"cafile" => __DIR__ . DIRECTORY_SEPARATOR . "cacert.pem",
		'ciphers' => 'HIGH:TLSv1.2:TLSv1.1:TLSv1.0:!SSLv3:!SSLv2',
	]
]);

$discord->setIntents(IntentsManager::allIntentsSum());

$discord->registerEvents(new class implements EventListener {
	public function onSend(MessageSendEvent $event) {
		if ($event->getMessage()->getMember()->isHuman()) $event->getChannel()->send("This is the default bot message! You should edit the source code.");
	}
});

$discord->login("your bot token");