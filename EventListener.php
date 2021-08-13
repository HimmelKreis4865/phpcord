<?php

use phpcord\channel\BaseTextChannel;
use phpcord\channel\embed\components\RGB;
use phpcord\channel\embed\MessageEmbed;
use phpcord\channel\TextMessage;
use phpcord\client\Activity;
use phpcord\command\SlashCommand;
use phpcord\command\SubCommand;
use phpcord\Discord;
use phpcord\event\client\ClientReadyEvent;
use phpcord\event\guild\GuildCreateEvent;
use phpcord\event\member\InteractionCreateEvent;
use phpcord\event\member\MemberAddEvent;
use phpcord\event\user\MemberRemoveEvent;
use phpcord\interaction\Interaction;
use phpcord\task\ClosureTask;
use phpcord\task\TaskManager;

class EventListener implements \phpcord\event\EventListener {
	
	public function onReady(ClientReadyEvent $event) {
		
		$event->getClient()->getCommandMap()->addHandler("ping", function (Interaction $interaction) {
			$message = new MessageEmbed();
			$ping = Discord::getInstance()->getClient()->getPing();
			$message->setColor(match (true) {
				($ping < 100) => RGB::fromArray(RGB::COLOR_GREEN),
				($ping < 150) => RGB::fromArray(RGB::COLOR_ORANGE),
				default => RGB::fromArray(RGB::COLOR_RED)
			})->setDescription("🏓 My ping is currently at **" . $ping . "ms**");
			$message->setFooter($interaction->getMember()->getTag(), $interaction->getMember()->getAvatarURL());
			
			$interaction->reply($message);
		});
	}
	
	public function onGuildAdd(GuildCreateEvent $event) {
		$event->getGuild()->registerSlashCommand(new SlashCommand("ping", SlashCommand::DEFAULT, "Returns my current ping"));
	}
}