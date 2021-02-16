<?php

use phpcord\channel\BaseTextChannel;
use phpcord\command\Command;
use phpcord\Discord;
use phpcord\event\EventListener;
use phpcord\event\message\MessageSendEvent;
use phpcord\guild\GuildMessage;
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
		if ($event->getMessage()->getMember()->isHuman()) $event->getChannel()->send("test");
	}
});

$discord->enableCommandMap();

$discord->getCommandMap()->register(new class extends Command {

	public function __construct() {
		parent::__construct("clear");
	}

	public function execute(BaseTextChannel $channel, GuildMessage $message, array $args): void {
		
	}
});

$discord->login("ODAwMjkzMzM2ODU3ODM3NTY5.YAQBQA.DljzfLFPS6F9q2yRQhuJ4mX2aqA");
