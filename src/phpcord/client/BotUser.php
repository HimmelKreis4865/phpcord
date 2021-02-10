<?php

namespace phpcord\client;

use phpcord\user\User;

class BotUser extends User {
	/** @var int $version */
	public $version = 6;

	/** @var array $userSettings */
	public $userSettings = [];

	/** @var bool $verified */
	public $verified = false;

	/** @var bool $mfa_enabled */
	public $mfa_enabled = false;

	/** @var null | string $email */
	public $email = null;

	/** @var null | string $sessionId */
	public $sessionId = null;

	/** @var array $relationShips */
	public $relationShips = [];

	/** @var array $privateChannels */
	public $privateChannels = [];

	/** @var string[] $guilds */
	public $guilds = [];

	/** @var array $guild_join_requests */
	public $guild_join_requests = [];

	/** @var string[] $geo_ordered_rtc_regions */
	public $geo_ordered_rtc_regions = ["europe", "russia", "us-east", "india", "us-central"];

	/** @var null | Application $application */
	public $application = null;

	/**
	 * BotUser constructor.
	 *
	 * @param string $guild_id
	 * @param string $id
	 * @param string $username
	 * @param string $discriminator
	 * @param int $public_flags
	 * @param string|null $avatar
	 * @param int $version
	 * @param array $userSettings
	 * @param bool $verified
	 * @param bool $mfa_enabled
	 * @param string|null $email
	 * @param string|null $sessionId
	 * @param array $relationShips
	 * @param array $privateChannels
	 * @param array $guilds
	 * @param array $guild_join_requests
	 * @param array $geo_ordered_rtc_regions
	 * @param Application|null $application
	 */
	public function __construct(string $guild_id, string $id, string $username, string $discriminator, int $public_flags = 0, ?string $avatar = null, int $version = 6, array $userSettings = [], bool $verified = false, bool $mfa_enabled = false, ?string $email = null, ?string $sessionId = null, array $relationShips = [], array $privateChannels = [], array $guilds = [], array $guild_join_requests = [], array $geo_ordered_rtc_regions = [], Application $application = null) {
		if (!$application instanceof Application) throw new \InvalidArgumentException("Cannot create a bot without valid application!");
		parent::__construct($guild_id, $id, $username, $discriminator, $public_flags, $avatar);
		$this->version = $version;
		$this->userSettings = $userSettings;
		$this->verified = $verified;
		$this->mfa_enabled = $mfa_enabled;
		$this->email = $email;
		$this->sessionId = $sessionId;
		$this->relationShips = $relationShips;
		$this->privateChannels = $privateChannels;
		$this->guilds = $guilds;
		$this->guild_join_requests = $guild_join_requests;
		$this->geo_ordered_rtc_regions = $geo_ordered_rtc_regions;
		$this->application = $application;
		// can be hardcoded since it has to be a bot
		$this->bot = true;
	}
}