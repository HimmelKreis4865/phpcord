<?php

namespace phpcord\guild;

use phpcord\user\User;

class FollowWebhook extends Webhook {
	/** @var string $sourceGuildId */
	public $sourceGuildId;
	
	/** @var string $sourceGuildName */
	public $sourceGuildName;
	
	/** @var string $sourceGuildIcon */
	public $sourceGuildIcon;
	
	/** @var string $sourceChannelId */
	public $sourceChannelId;
	
	/** @var string $sourceChannelName */
	public $sourceChannelName;
	
	/**
	 * FollowWebhook constructor.
	 *
	 * @param string $guildId
	 * @param string $id
	 * @param string $channelId
	 * @param string $sourceGuildId
	 * @param string $sourceGuildName
	 * @param string $sourceGuildIcon
	 * @param string $sourceChannelId
	 * @param string $sourceChannelName
	 * @param string|null $name
	 * @param string|null $avatar
	 * @param string|null $token
	 * @param string|null $application_id
	 * @param User|null $creator
	 */
	public function __construct(string $guildId, string $id, string $channelId, string $sourceGuildId, string $sourceGuildName, string $sourceGuildIcon, string $sourceChannelId, string $sourceChannelName, ?string $name = null, ?string $avatar = null, ?string $token = null, ?string $application_id = null, ?User $creator = null) {
		$this->sourceGuildId = $sourceGuildId;
		$this->sourceGuildName = $sourceGuildName;
		$this->sourceGuildIcon = $sourceGuildIcon;
		$this->sourceChannelId = $sourceChannelId;
		$this->sourceChannelName = $sourceChannelName;
		parent::__construct($guildId, $id, $channelId, $name, $avatar, $token, $application_id, $creator);
	}
	
	/**
	 * Returns the id of the source guild
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getSourceGuildId(): string {
		return $this->sourceGuildId;
	}
	
	/**
	 * Returns the name of the source guild
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getSourceGuildName(): string {
		return $this->sourceGuildName;
	}
	
	/**
	 * Returns the icon url of the source guild
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getSourceGuildIcon(): string {
		return $this->sourceGuildIcon;
	}
	
	/**
	 * Returns the id of the source channel
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getSourceChannelId(): string {
		return $this->sourceChannelId;
	}
	
	/**
	 * Returns the name of the source channel
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getSourceChannelName(): string {
		return $this->sourceChannelName;
	}
}