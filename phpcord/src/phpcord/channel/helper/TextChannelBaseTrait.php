<?php

/*
 *         .__                                       .___
 * ______  |  |__  ______    ____   ____ _______   __| _/
 * \____ \ |  |  \ \____ \ _/ ___\ /  _ \\_  __ \ / __ |
 * |  |_> >|   Y  \|  |_> >\  \___(  <_> )|  | \// /_/ |
 * |   __/ |___|  /|   __/  \___  >\____/ |__|   \____ |
 * |__|         \/ |__|         \/                    \/
 *
 *
 * This library is developed by HimmelKreis4865 © 2022
 *
 * https://github.com/HimmelKreis4865/phpcord
 */

namespace phpcord\channel\helper;

use phpcord\async\completable\Completable;
use phpcord\channel\GuildChannel;
use phpcord\http\RestAPI;
use phpcord\message\Message;
use phpcord\message\Sendable;
use phpcord\utils\Timestamp;

trait TextChannelBaseTrait {
	
	/**
	 * Before you shit-talk, traits cannot have constants
	 *
	 * @var int $MAX_BULK_DELETE_TIMESTAMP
	 */
	private static int $MAX_BULK_DELETE_TIMESTAMP = (60 * 60 * 24 * 14);
	
	/**
	 * @param int $id
	 *
	 * @return Completable<Message>
	 */
	public function fetchMessage(int $id): Completable {
		return RestAPI::getInstance()->getMessage($this->getId(), $id, ($this instanceof GuildChannel ? $this->getGuildId() : null));
	}
	
	
	/**
	 * @param SearchFilter|null $filter
	 *
	 * @return Completable<array<Message>>
	 */
	public function fetchMessages(SearchFilter $filter = null): Completable {
		return RestAPI::getInstance()->getMessages($this->getId(), $filter->asArray());
	}
	
	/**
	 * @param Sendable $sendable
	 *
	 * @return Completable
	 */
	public function send(Sendable $sendable): Completable {
		return RestAPI::getInstance()->sendMessage($this->getId(), $sendable);
	}
	
	/**
	 * @return Completable
	 */
	public function triggerTyping(): Completable {
		return RestAPI::getInstance()->triggerTyping($this->getId());
	}
	
	/**
	 * @param int $id
	 * @param string|null $reason
	 *
	 * @return Completable
	 */
	public function deleteMessage(int $id, string $reason = null): Completable {
		return RestAPI::getInstance()->deleteMessage($this->getId(), $id, $reason);
	}
	
	/**
	 * @param SearchFilter $filter
	 * @param string|null $reason
	 *
	 * @return Completable
	 */
	public function bulkDelete(SearchFilter $filter, string $reason = null): Completable {
		$completable = Completable::sync();
		$this->fetchMessages($filter)->then(function (array $messages) use ($completable, $reason): void {
			$ids = [];
			/** @var Message $message */
			foreach ($messages as $message) {
				if ($message->getCreateTimestamp()->diff(Timestamp::now()) > self::$MAX_BULK_DELETE_TIMESTAMP) continue;
				$ids[] = $message->getId();
			}
			RestAPI::getInstance()->bulkDelete($this->getId(), $ids, $reason)->then(fn() => $completable->complete(true))->catch(fn($err) => $completable->complete($err));
		});
		return $completable;
	}
	
	/**
	 * @return int
	 */
	abstract public function getId(): int;
}