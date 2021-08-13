<?php

namespace commands;

use phpcord\channel\BaseTextChannel;
use phpcord\channel\embed\MessageEmbed;
use phpcord\command\Command;
use phpcord\Discord;
use phpcord\guild\GuildMessage;

class UptimeCommand extends Command {
	
	public function __construct() {
		parent::__construct("uptime");
	}
	
	public function execute(BaseTextChannel $channel, GuildMessage $message, array $args): void {
		$channel->send((new MessageEmbed())->setTitle("🕐 UPTIME")->setColor("#7289da")->setDescription("The bot currently has an Uptime of **" . Discord::getInstance()->getClient()->getUptime() . "**")->setFooter($message->getMember()->getTag(), $message->getMember()->getAvatarURL()));
	}
}