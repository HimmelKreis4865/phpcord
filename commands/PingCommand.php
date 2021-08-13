<?php

namespace commands;

use phpcord\channel\BaseTextChannel;
use phpcord\channel\embed\MessageEmbed;
use phpcord\channel\TextMessage;
use phpcord\command\Command;
use phpcord\Discord;
use phpcord\guild\component\ActionRow;
use phpcord\guild\component\Button;
use phpcord\guild\GuildMessage;
use function var_dump;

class PingCommand extends Command {
	
	public function __construct() {
		parent::__construct("ping");
	}
	
	public function execute(BaseTextChannel $channel, GuildMessage $message, array $args): void {
		$components = [ new ActionRow([(new Button("Test content", Button::STYLE_SUCCESS))->setCustomId("test_button")]) ];
		
		$channel->send((new MessageEmbed())->setTitle("🏓 PING")->setColor("#7289da")->setDescription("The bot currently has a ping average of **" . Discord::getInstance()->getClient()->getPing() . "ms**")->setFooter($message->getMember()->getTag(), $message->getMember()->getAvatarURL()), $components)->catch(function ($error) {
			var_dump($error);
		});
	}
}