<?php

namespace phpcord\intents\handlers;

use phpcord\channel\BaseTextChannel;
use phpcord\channel\DMChannel;
use phpcord\Discord;
use phpcord\event\message\DMMessageDeleteEvent;
use phpcord\event\message\DMMessageSendEvent;
use phpcord\event\message\DMMessageUpdateEvent;
use phpcord\event\message\MessageDeleteEvent;
use phpcord\event\message\MessageSendEvent;
use phpcord\event\message\MessageUpdateEvent;
use phpcord\guild\GuildMember;
use phpcord\utils\MessageInitializer;
use function is_null;

class MessageHandler extends BaseIntentHandler {

	public function getIntents(): array {
		return ["MESSAGE_CREATE", "MESSAGE_UPDATE", "MESSAGE_DELETE"];
	}

	public function handle(Discord $discord, string $intent, array $data) {
		if (($dmChannel = $discord->getClient()->getDMChannel($data["channel_id"] ?? "-1")) instanceof DMChannel) {
			$this->handleDM($discord, $intent, $dmChannel, $data);
			return;
		}
		switch ($intent) {
			case "MESSAGE_CREATE":
				$message = MessageInitializer::create($data);
				/** @var BaseTextChannel $channel thanks phpstorm :/ */
  				$channel = $discord->client->getGuild($message->guildId)->getChannel($message->channelId);
				
				if (isset($discord->answerHandlers[$message->channelId . ":" . $message->getMember()->getId()])) {
					$val = $discord->answerHandlers[$message->channelId . ":" . $message->getMember()->getId()];
					$callable = $val->answerCallable;
					$callable($channel, $message);
					unset($discord->answerHandlers[$message->channelId . ":" . $message->getMember()->getId()]);
					return;
				}
  				
  				// calling CommandMap to check for a command
				$discord->getCommandMap()->executeCommand($channel, $message);

				(new MessageSendEvent($message, $channel))->call();
				break;
			case "MESSAGE_UPDATE":
				$message = MessageInitializer::createUpdated($data);
   				$channel = $discord->client->getGuild($message->guild_id)->getChannel($message->channel_id);
				(new MessageUpdateEvent($message, $channel))->call();
				break;
			case "MESSAGE_DELETE":
				$message = MessageInitializer::createDeleted($data);
   				$channel = $discord->client->getGuild($message->guild_id)->getChannel($message->channel_id);
				(new MessageDeleteEvent($message, $channel))->call();
		}
	}
	
	public function handleDM(Discord $discord, string $intent, DMChannel $channel, array $data) {
		switch ($intent) {
			case "MESSAGE_CREATE":
				$message = MessageInitializer::create($data);
				
				$id = @$message->getMember()->getId();
				if (is_null($id)) return;
				
				if (isset($discord->answerHandlers[$message->channelId . ":" . $id])) {
					$val = $discord->answerHandlers[$message->channelId . ":" . $id];
					$callable = $val->answerCallable;
					$callable($channel, $message);
					unset($discord->answerHandlers[$message->channelId . ":" . $id]);
					return;
				}
				
				// calling CommandMap to check for a command
				$discord->getCommandMap()->executeCommand($channel, $message);
				
				(new MessageSendEvent($message, $channel))->call();
				break;
			case "MESSAGE_UPDATE":
				$message = MessageInitializer::createUpdated($data);
				(new MessageUpdateEvent($message, $channel))->call();
				break;
			case "MESSAGE_DELETE":
				$message = MessageInitializer::createDeleted($data);
				(new MessageDeleteEvent($message, $channel))->call();
		}
	}
}