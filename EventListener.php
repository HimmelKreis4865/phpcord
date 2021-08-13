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
	
	public function onAdd(MemberAddEvent $event) {
		/** @var BaseTextChannel $channel */
		if (($channel = $event->getGuild()->getChannel("816776396919013397")) instanceof BaseTextChannel) {
			var_dump($event->getMember()->getAvatarURL());
			$channel->send((new MessageEmbed())->setTitle("Welcome")->setDescription("Welcome to this server, " . $event->getMember()->createMention())->setColor("#4dff6a")->setThumbnail($event->getMember()->getAvatarURL()));
		}
	}
	
	public function onRemove(MemberRemoveEvent $event) {
		var_dump("member removed");
		/** @var BaseTextChannel $channel */
		if (($channel = $event->getGuild()->getChannel("816776396919013397")) instanceof BaseTextChannel) {
			$channel->send((new MessageEmbed())->setTitle("Goodbye")->setDescription("Hope you had a great time, " . $event->getUser()->getTag())->setColor("#ff504d")->setThumbnail($event->getUser()->getAvatarURL()));
		}
	}
	//
	public function onInteract(InteractionCreateEvent $event) {
		//var_dump($event->getInteraction());
	}
	
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
			
			/*TaskManager::getInstance()->submitTask(new ClosureTask(function () use ($interaction) {
				$message = new MessageEmbed();
				$message->setTitle("Ban success")->setColor(new RGB(0, 255))->setDescription("The ban succeed!");
				$message->setFooter($interaction->getMember()->getTag(), $interaction->getMember()->getAvatarURL());
				
				$interaction->edit($message)->then(function () {
					var_dump("reply sent");
				});
			}, 80));*/
		});
	}
	
	public function onGuildAdd(GuildCreateEvent $event) {
		$event->getGuild()->getSlashCommands()->then(function (array $commands) {
			/** @var SlashCommand[] $commands */
			foreach ($commands as $command) {
				var_dump("we have " . $command->getName());
				$command->delete();
			}
		});
		
		/*$event->getGuild()->registerSlashCommand(new SlashCommand("ban", SlashCommand::DEFAULT, "Bans players with ease", [
			new SubCommand("target", SubCommand::USER, "The target player to ban", true, [], []),
			new SubCommand("reason", SubCommand::INTEGER, "The ban reason ID", true, [
				"offensive" => 1,
				"permanent" => 99
			])
		]));*/
		
		$event->getGuild()->registerSlashCommand(new SlashCommand("ping", SlashCommand::DEFAULT, "Returns my current ping"));
		
		$event->getGuild()->registerSlashCommand(new SlashCommand("test", SlashCommand::DEFAULT, "test command", [
			new SubCommand("channel", SubCommand::CHANNEL, "Shows channel blablabla"),
			new SubCommand("role", SubCommand::ROLE, "Shows role blablabla"),
			new SubCommand("mentionable", SubCommand::MENTIONABLE, "Shows mentionables blablabla"),
		]));
	}
}
/*
 *
 * new SubCommand("reason", SubCommand::INTEGER, "The ban reason ID", true, [
					"offensive" => 1,
					"permanent" => 99
				])*/