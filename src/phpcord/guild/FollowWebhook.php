<?php

namespace phpcord\guild;

use phpcord\user\User;

class FollowWebhook extends Webhook {
	public $sourceGuildId;
	
	public $sourceGuildName;
	
	public $sourceGuildIcon;
	
	public $sourceChannelId;
	
	public $sourceChannelName;
	
	public function __construct(string $guildId, string $id, string $channelId, string $sourceGuildId, string $sourceGuildName, string $sourceGuildIcon, string $sourceChannelId, string $sourceChannelName, ?string $name = null, ?string $avatar = null, ?string $token = null, ?string $application_id = null, ?User $creator = null) {
		$this->sourceGuildId = $sourceGuildId;
		$this->sourceGuildName = $sourceGuildName;
		$this->sourceGuildIcon = $sourceGuildIcon;
		$this->sourceChannelId = $sourceChannelId;
		$this->sourceChannelName = $sourceChannelName;
		parent::__construct($guildId, $id, $channelId, $name, $avatar, $token, $application_id, $creator);
	}
	
	/**
	 * @return string
	 */
	public function getSourceGuildId(): string {
		return $this->sourceGuildId;
	}
	
	/**
	 * @return string
	 */
	public function getSourceGuildName(): string {
		return $this->sourceGuildName;
	}
	
	/**
	 * @return string
	 */
	public function getSourceGuildIcon(): string {
		return $this->sourceGuildIcon;
	}
	
	/**
	 * @return string
	 */
	public function getSourceChannelId(): string {
		return $this->sourceChannelId;
	}
	
	/**
	 * @return string
	 */
	public function getSourceChannelName(): string {
		return $this->sourceChannelName;
	}
}