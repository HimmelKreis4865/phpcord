<?php

use phpcord\Discord;
use phpcord\event\message\MessageSendEvent;
use phpcord\message\sendable\MessageBuilder;

require 'phpcord/src/phpcord/Discord.php';

$discord = new Discord();

$discord->listen(MessageSendEvent::class, function (MessageSendEvent $event): void {
	if (!$event->getMessage()->getAuthor()->isBot()) {
		$event->getMessage()->reply(MessageBuilder::build('This is a dummy message.'));
	}
});

$discord->login('your bot token');