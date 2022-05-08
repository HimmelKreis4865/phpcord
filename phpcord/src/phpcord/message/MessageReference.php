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

namespace phpcord\message;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use JsonSerializable;

final class MessageReference implements JsonSerializable {
	
	/**
	 * @param int $id
	 * @param int|null $channelId
	 * @param int|null $guildId
	 */
	public function __construct(private int $id, private ?int $channelId = null, private ?int $guildId = null) { }
	
	/**
	 * @param Message $message
	 *
	 * @return MessageReference
	 */
	#[Pure] public static function fromMessage(Message $message): MessageReference {
		return new MessageReference($message->getId(), $message->getChannelId(), $message->getGuildId());
	}
	
	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
	}
	
	/**
	 * @return int|null
	 */
	public function getGuildId(): ?int {
		return $this->guildId;
	}
	
	/**
	 * @return int
	 */
	public function getChannelId(): int {
		return $this->channelId;
	}
	
	#[ArrayShape(['message_id' => "int", 'channel_id' => "int", 'guild_id' => "int|null"])] public function jsonSerialize(): array {
		return [
			'message_id' => $this->id,
			'channel_id' => $this->channelId,
			'guild_id' => $this->guildId
		];
	}
}