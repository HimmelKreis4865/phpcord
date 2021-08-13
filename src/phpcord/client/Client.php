<?php

namespace phpcord\client;

use DateTime;
use InvalidArgumentException;
use phpcord\channel\DMChannel;
use phpcord\command\GlobalCommandMap;
use phpcord\Discord;
use phpcord\guild\Guild;
use phpcord\guild\GuildInvite;
use phpcord\http\RestAPIHandler;
use phpcord\user\User;
use phpcord\utils\GuildSettingsInitializer;
use phpcord\task\Promise;
use Threaded;
use function array_rand;
use function get_class;
use function is_array;
use function is_string;
use function json_decode;

class Client {
	/** @var Guild[]|null $guild todo: maybe make this to a SplFixedArray */
	public $guilds;
	
	/** @var int $ping */
	public $ping = 0;
	
	/** @var BotUser|null $user */
	public $user;
	
	/** @var null | string $sessionId */
	public $sessionId = null;
	
	/** @var DMChannel[] $dms */
	public $dms = [];

	/** @var DateTime $startTime the starttime in seconds */
	protected $startTime;
	
	protected GlobalCommandMap $commandMap;
	
	/**
	 * Client constructor.
	 *
	 * @param Guild[] $guilds
	 */
	public function __construct(array $guilds = []) {
		$this->guilds = $guilds;
		$this->commandMap = new GlobalCommandMap();
		$this->startTime = new DateTime("now");
	}

	/**
	 * @return Guild[]
	 */
	public function getGuilds(): array {
		return $this->guilds;
	}
	
	/**
	 * @return GlobalCommandMap
	 */
	public function getCommandMap(): GlobalCommandMap {
		return $this->commandMap;
	}

	/**
	 * Gets a guild by ID or returns a random id (if id is null)
	 *
	 * @api
	 *
	 * @param string|null $id
	 *
	 * @return Guild|null Returns null if there is no existing guild
	 */
	public function getGuild(string $id = null): ?Guild {
		if ($id !== null and isset($this->guilds[$id])) return $this->guilds[$id];
		if (count($this->guilds) === 0) return null;
		return $this->guilds[array_rand($this->guilds)];
	}
	
	/**
	 * Fetches a guild from discord rest api, does not store it to cache
	 *
	 * @api
	 *
	 * @param string $id
	 * @param bool $withCount
	 *
	 * @return Promise
	 */
	public function fetchGuild(string $id, bool $withCount = true): Promise {
		return RestAPIHandler::getInstance()->fetchGuild($id, $withCount);
	}
	
	/**
	 * Returns the last recognized ping (in MS)
	 * => ping is built by time between heartbeating and heartbeat ack
	 *
	 * @api
	 *
	 * @return int
	 */
	public function getPing(): int {
		return $this->ping;
	}

	/**
	 * Returns the BotUser instance of the Client
	 *
	 * @api
	 *
	 * @return BotUser|null
	 */
	public function getUser(): ?BotUser {
		return $this->user;
	}
	
	/**
	 * Gets an Invitation by a Code
	 *
	 * @api
	 *
	 * @param string $code
	 *
	 * @return Promise
	 */
	public function getInvite(string $code): Promise {
		return RestAPIHandler::getInstance()->getInvite($code);
	}
	
	/**
	 * Returns the uptime of the bot in a specific format
	 * -> https://www.php.net/manual/de/dateinterval.format.php
	 * Check this link for a list of all valid formats
	 *
	 * @api
	 *
	 * @param string $format
	 *
	 * @return string
	 */
	public function getUptime(string $format = "%H:%I:%S"): string {
		$now = new DateTime("now");
		$interval = $this->startTime->diff($now);
		return $interval->format($format);
	}
	
	/**
	 * Deletes an Invitation by a specific code
	 *
	 * @api
	 *
	 * @param string $code
	 *
	 * @return Promise
	 */
	public function deleteInvite(string $code): Promise {
		return RestAPIHandler::getInstance()->deleteInvite($code);
	}
	
	/**
	 * Changes the activity of the bot
	 * Public on all guilds and DMs
	 *
	 * @api
	 *
	 * @param Activity $activity
	 */
	public function setActivity(Activity $activity) {
		$activity = $activity->encode();
		
		Discord::getInstance()->pushToSocket($activity);
	}
	
	/**
	 * Adds a DM channel to the cache
	 *
	 * @internal
	 *
	 * @param DMChannel $channel
	 */
	public function addDMChannel(DMChannel $channel) {
		$this->dms[$channel->getId()] = $channel;
	}
	
	/**
	 * Returns a DM channel by ID
	 *
	 * @api
	 *
	 * @param int $id
	 *
	 * @return DMChannel|null
	 */
	public function getDMChannel(int $id): ?DMChannel {
		return @$this->dms[$id];
	}
	
	/**
	 * Returns an array with all dms a user is involved
	 *
	 * @param User|string $user
	 *
	 * @return array
	 */
	public function getDMsWithUser($user): array {
		if ($user instanceof User) $user = $user->getId();
		if (!is_string($user)) throw new InvalidArgumentException("Expected value of type string or User, not " . get_class($user));
		return array_filter($this->dms, function(DMChannel $channel) use ($user) {
			foreach ($channel->getRecipients() as $recipient) {
				if ($recipient->getId() === $user) return true;
			}
			return false;
		});
	}
	
	/**
	 * Returns an arry with all dm channels
	 *
	 * @api
	 *
	 * @return DMChannel[]
	 */
	public function getDMs(): array {
		return $this->dms;
	}
}