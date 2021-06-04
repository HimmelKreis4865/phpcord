<?php

namespace phpcord\channel;

use OutOfBoundsException;
use phpcord\guild\GuildChannel;
use phpcord\guild\MessageSentPromise;
use phpcord\guild\store\GuildStoredMessage;
use phpcord\http\RestAPIHandler;
use phpcord\utils\MessageInitializer;
use Promise\Promise;
use RuntimeException;
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
	 * @param string|Sendable $message
	 *
	 * @return Promise
	 */
	public function send($message): Promise {
		if (is_string($message) or is_numeric($message)) $message = new TextMessage(strval($message));
		if (!$message instanceof Sendable)
			throw new RuntimeException("Expected subclass of interface " . Sendable::class);
		return RestAPIHandler::getInstance()->sendMessage($this->getGuildId(), $this->getId(), $message->getFormattedData(), $message->getContentType());
	}
	
	/**
	 * Returns fetched message from RESTAPI, since fetching is slow, please don't fetch useless messages or you'll have lags
	 *
	 * @api
	 *
	 * @param int $limit
	 *
	 * @return Promise
	 */
	public function getMessages(int $limit = 50): Promise {
		if ($limit < 1 or $limit > 100) throw new OutOfBoundsException("You can only get 1-100 messages per call!");

		return RestAPIHandler::getInstance()->getMessages($this->getId(), $this->getGuildId(), $limit);
	}
	
	/**
	 * Returns a message by ID or null if none existed
	 *
	 * @api
	 *
	 * @param string $id
	 *
	 * @return Promise
	 */
	public function getMessage(string $id): Promise {
		return RestAPIHandler::getInstance()->getMessage($this->getId(), $this->getGuildId(), $id);
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
	public function getMessageIds(int $limit = 50): Promise {
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