<?php

namespace phpcord\channel;

use OutOfBoundsException;
use phpcord\guild\GuildChannel;
use phpcord\guild\MessageSentPromise;
use phpcord\guild\store\GuildStoredMessage;
use phpcord\http\RestAPIHandler;
use phpcord\utils\MessageInitializer;
use function json_decode;

abstract class BaseTextChannel extends GuildChannel {
	
	/** @var string|null $last_message_id */
	public $last_message_id = null;
	
	/**
	 * BaseTextChannel constructor.
	 *
	 * @param string $guild_id
	 * @param string $id
	 * @param string $name
	 * @param int $position
	 * @param array $permissions
	 * @param string|null $last_message_id
	 */
	public function __construct(string $guild_id, string $id, string $name, int $position = 0, array $permissions = [], ?string $last_message_id = null) {
		parent::__construct($guild_id, $id, $name, $position, $permissions);
		$this->last_message_id = $last_message_id;
	}
	
	/**
	 * Returns the id of the channel
	 *
	 * @warning for dm: not the user id, even tho it's just a dm
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getId(): string {
		return $this->id;
	}
	
	/**
	 * Returns the last message id of the channel (when fetching, it's not yet updated!)
	 *
	 * @api
	 *
	 * @return string|null
	 */
	public function getLastMessageId(): ?string {
		return $this->last_message_id;
	}
	
	/**
	 * Sends a message in the given channel, does not work in DMs yet
	 *
	 * @api
	 *
	 * @param $message
	 *
	 * @return MessageSentPromise
	 */
	public function send($message): MessageSentPromise {
		if (is_string($message) or is_numeric($message)) $message = new TextMessage(strval($message));
		if (!$message instanceof Sendable) return new MessageSentPromise(true);
		$result = RestAPIHandler::getInstance()->sendMessage($this->id, $message->getFormattedData(), $message->getContentType());
		if (!$result or $result->isFailed()) return new MessageSentPromise(true);
		if (!($result = json_decode($result->getRawData(), true))) return new MessageSentPromise(true);
		return new MessageSentPromise(false, MessageInitializer::fromStore($this->getGuildId(), $result), $this->getId());
	}
	
	/**
	 * Returns fetched message from RESTAPI, since fetching is slow, please don't fetch useless messages or you'll have lags
	 *
	 * @api
	 *
	 * @param int $limit
	 *
	 * @return array
	 */
	public function getMessages(int $limit = 50): array {
		if ($limit < 1 or $limit > 100) throw new OutOfBoundsException("You can only get 1-100 messages per call!");

		$response = json_decode(RestAPIHandler::getInstance()->getMessages($this->id, $limit)->getRawData(), true);
		$messages = [];

		foreach ($response as $value) {
			$msg = MessageInitializer::fromStore($this->getGuildId(), $value);
			$messages[$msg->id] = $msg;
		}

		return $messages;
	}
	
	/**
	 * Returns a message by ID or null if none existed
	 *
	 * @api
	 *
	 * @param string $id
	 *
	 * @return GuildStoredMessage|null
	 */
	public function getMessage(string $id): ?GuildStoredMessage {
		$result = RestAPIHandler::getInstance()->getMessage($this->getId(), $id);
		if ($result->isFailed()) return null;
		if (!is_array(($array = @json_decode($result->getRawData(), true)))) return null;
		return MessageInitializer::fromStore($this->getGuildId(), $array);
	}
	
	/**
	 * Returns the ids of the messages by limit @see BaseTextChannel::getMessages() for a detailed instruction
 	 *
	 * @api
	 *
	 * @param int $limit
	 *
	 * @return array
	 */
	public function getMessageIds(int $limit = 50): array {
		return array_keys($this->getMessages($limit));
	}
	
	/**
	 * Will trigger typing in the given channel
	 * If no message is sent, this trigger will keep up for 10 seconds
	 *
	 * @api
	 *
	 * @return bool
	 */
	public function triggerTyping(): bool {
		return !RestAPIHandler::getInstance()->triggerTyping($this->getId())->isFailed();
	}
}