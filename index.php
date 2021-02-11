<?php

use phpcord\channel\BaseTextChannel;
use phpcord\command\Command;
use phpcord\Discord;
use phpcord\event\EventListener;
use phpcord\event\message\MessageSendEvent;
use phpcord\guild\GuildMessage;
use phpcord\intents\IntentsManager;

require_once __DIR__ . "/src/phpcord/Discord.php";

$discord = new Discord(__DIR__, [
	"debugMode" => true
]);

$discord->setIntents(IntentsManager::allIntentsSum());

$discord->registerEvents(new class implements EventListener {
	public function onSend(MessageSendEvent $event) {
		
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

$discord->login("token");
