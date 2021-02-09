<?php

namespace phpcord\channel;

use phpcord\guild\GuildChannel;
use phpcord\guild\GuildMessage;
use phpcord\guild\MessageSentPromise;
use phpcord\guild\store\GuildStoredMessage;
use phpcord\http\RestAPIHandler;
use phpcord\utils\MessageInitializer;
use function json_decode;

abstract class BaseTextChannel extends GuildChannel {

	public $last_message_id = null;

	public $topic = null;

	public $parent_id = null;

	public $nsfw;

	public function __construct(string $guild_id, string $id, string $name, int $position = 0, array $permissions = [], bool $nsfw = false, ?string $last_message_id = null, ?string $topic = null, ?string $parent_id = null) {
		parent::__construct($guild_id, $id, $name, $position, $permissions);
		$this->nsfw = $nsfw;
		$this->last_message_id = $last_message_id;
		$this->topic = $topic;
		$this->parent_id = $parent_id;
	}

	public function send($message): MessageSentPromise {
		if (is_string($message) or is_numeric($message)) $message = new TextMessage(strval($message));
		if (!$message instanceof Sendable) return new MessageSentPromise(true);
		$content = json_encode($message->getJsonData());
		$result = RestAPIHandler::getInstance()->sendMessage($this->id, $content);
		if (!$result or $result->isFailed()) return new MessageSentPromise(true);
		if (!json_decode($result->getRawData(), true)) return new MessageSentPromise(true);

		return new MessageSentPromise(false, $this->getGuildId(), $this->getId());
	}

	public function getMessages(int $limit = 50): array {
		if ($limit < 1 or $limit > 100) throw new \OutOfBoundsException("You can only get 1-100 messages per call!");

		$response = json_decode(RestAPIHandler::getInstance()->getMessages($this->id, $limit)->getRawData(), true);
		$messages = [];

		foreach ($response as $value) {
			$msg = MessageInitializer::fromStore($this->getGuildId(), $value);
			$messages[$msg->id] = $msg;
		}

		return $messages;
	}
	
	public function getMessage(string $id): ?GuildStoredMessage {
		$result = RestAPIHandler::getInstance()->getMessage($this->getId(), $id);
		if ($result->isFailed()) return null;
		if (!is_array(($array = @json_decode($result->getRawData(), true)))) return null;
		return MessageInitializer::fromStore($this->getGuildId(), $array);
	}

	public function getMessageIds(int $limit = 50): array {
		return array_keys($this->getMessages($limit));
	}
}


