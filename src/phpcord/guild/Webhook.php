<?php

namespace phpcord\guild;

use phpcord\Discord;
use phpcord\http\RestAPIHandler;
use phpcord\user\User;

final class Webhook {
	
	public const TYPE_INCOMING = 1;
	
	public const TYPE_FOLLOW = 2;
	
	/** @var string $id the guild id this webhook is for */
	protected $guildId;
	
	/** @var string $id the id of the webhook */
	protected $id;
	
	/** @var string $guildId the channel id this webhook is for */
	protected $channelId;
	
	/** @var User|null $creator the user this webhook was created by (not returned when getting a webhook with its token) */
	protected $creator = null;
	
	/** @var string|null $avatar the default name of the webhook */
	protected $avatar = null;
	
	/** @var string|null $name the default avatar of the webhook */
	protected $name;
	
	/** @var string|null $token the secure token of the webhook (returned for Incoming Webhooks) */
	protected $token = null;
	
	/** @var string|null $application_id the bot/OAuth2 application that created this webhook */
	protected $application_id = null;
	
	/**
	 * Webhook constructor.
	 *
	 * @param string $guildId
	 * @param string $id
	 * @param string $channelId
	 * @param string|null $name
	 * @param string|null $avatar
	 * @param string|null $token
	 * @param string|null $application_id
	 * @param User|null $creator
	 */
	public function __construct(string $guildId, string $id, string $channelId, ?string $name = null, ?string $avatar = null, ?string $token = null, ?string $application_id = null, ?User $creator = null) {
		$this->guildId = $guildId;
		$this->id = $id;
		$this->channelId = $channelId;
		$this->name = $name;
		$this->avatar = $avatar;
		$this->token = $token;
		$this->application_id = $application_id;
		$this->creator = $creator;
	}
	
	/**
	 * @return string|null
	 */
	public function getName(): ?string {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getId(): string {
		return $this->id;
	}

	/**
	 * @return string|null
	 */
	public function getAvatar(): ?string {
		return $this->avatar;
	}

	/**
	 * @return string
	 */
	public function getGuildId(): string {
		return $this->guildId;
	}

	/**
	 * @return string
	 */
	public function getChannelId(): string {
		return $this->channelId;
	}

	/**
	 * @return string|null
	 */
	public function getApplicationId(): ?string {
		return $this->application_id;
	}

	/**
	 * @return User|null
	 */
	public function getCreator(): ?User {
		return $this->creator;
	}

	/**
	 * @return string|null
	 */
	public function getToken(): ?string {
		return $this->token;
	}
	
	public function getGuild(): ?Guild {
		return Discord::getInstance()->getClient()->getGuild($this->getGuildId());
	}

	public function delete(): bool {
		return !RestAPIHandler::getInstance()->deleteWebhook($this->getId())->isFailed();
	}
	
	public function modify(string $name, ?string $channelId = null, ?string $avatar = null): bool {
		return !RestAPIHandler::getInstance()->modifyWebhook($this->getId(), $name, $channelId, $avatar)->isFailed();
	}
	
	public function setName(string $name): bool {
		return $this->modify($name);
	}
	
	public function setAvatar(string $imageData): bool {
		return $this->modify(null, null, $imageData);
	}
	
	public function moveToChannel(string $channelId): bool {
		return $this->modify(null, $channelId);
	}
}


