<?php

namespace phpcord\guild;

use phpcord\Discord;
use phpcord\http\RestAPIHandler;
use phpcord\user\User;

class Webhook {
	
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
	 * Returns the name of the webhook application
	 *
	 * @api
	 *
	 * @return string|null
	 */
	public function getName(): ?string {
		return $this->name;
	}

	/**
	 * Returns the ID of the webhook
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getId(): string {
		return $this->id;
	}

	/**
	 * Returns the avatar contents / url of the webhook
	 *
	 * @api
	 *
	 * @return string|null
	 */
	public function getAvatar(): ?string {
		return $this->avatar;
	}

	/**
	 * Returns the GuildID the webhook was made for
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getGuildId(): string {
		return $this->guildId;
	}

	/**
	 * Returns the ChannelID the webhook was made for
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getChannelId(): string {
		return $this->channelId;
	}

	/**
	 * Returns the ID of the Application (the bot/OAuth2 that created the webhook)
	 *
	 * @api
	 *
	 * @return string|null
	 */
	public function getApplicationId(): ?string {
		return $this->application_id;
	}

	/**
	 * Returns the creator as User Instance
	 *
	 * @api
	 *
	 * @return User|null
	 */
	public function getCreator(): ?User {
		return $this->creator;
	}

	/**
	 * Returns the token of the webhook
	 *
	 * @api
	 *
	 * @return string|null
	 */
	public function getToken(): ?string {
		return $this->token;
	}
	
	/**
	 * Tries to get the guild from cache, won't fetch it from RESTAPI
	 *
	 * @api
	 *
	 * @return Guild|null
	 */
	public function getGuild(): ?Guild {
		return Discord::getInstance()->getClient()->getGuild($this->getGuildId());
	}
	
	/**
	 * Tries to delete the webhook, false on failure
	 *
	 * @api
	 *
	 * @return bool
	 */
	public function delete(): bool {
		return !RestAPIHandler::getInstance()->deleteWebhook($this->getId())->isFailed();
	}
	
	/**
	 * Modifies the webhook with specified parameters, null parameters will be left out
	 *
	 * @api
	 *
	 * @param string|null $name
	 * @param string|null $channelId
	 * @param string|null $avatar
	 *
	 * @return bool
	 */
	public function modify(?string $name, ?string $channelId = null, ?string $avatar = null): bool {
		return !RestAPIHandler::getInstance()->modifyWebhook($this->getId(), $name, $channelId, $avatar)->isFailed();
	}
	
	/**
	 * Changes the name of the webhook
	 *
	 * @api
	 *
	 * @param string $name
	 *
	 * @return bool
	 */
	public function setName(string $name): bool {
		return $this->modify($name);
	}
	
	/**
	 * Changes the avatar of the webhook to some data
	 *
	 * @api
	 *
	 * @param string $imageData
	 *
	 * @return bool
	 */
	public function setAvatar(string $imageData): bool {
		return $this->modify(null, null, $imageData);
	}
	
	/**
	 * Moves the webhook to another channel
	 *
	 * @api
	 *
	 * @param string $channelId
	 *
	 * @return bool
	 */
	public function moveToChannel(string $channelId): bool {
		return $this->modify(null, $channelId);
	}
}