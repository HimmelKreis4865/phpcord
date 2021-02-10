<?php

namespace phpcord\client;

use phpcord\Discord;
use phpcord\guild\Guild;
use phpcord\guild\GuildInvite;
use phpcord\http\RestAPIHandler;
use phpcord\utils\GuildSettingsInitializer;
use function array_rand;
use function is_array;
use function json_decode;

class Client {
	/** @var Guild[]|null $guild todo: maybe make this to a SplFixedArray */
	public $guilds;
	
	/** @var int $ping */
	public $ping;
	
	/** @var BotUser|null $user */
	public $user;

	/**
	 * Client constructor.
	 *
	 * @param Guild[] $guilds
	 */
	public function __construct(array $guilds = []) {
		$this->guilds = $guilds;
	}

	/**
	 * @return Guild[]
	 */
	public function getGuilds(): array {
		return $this->guilds;
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
	 * @return GuildInvite|null
	 */
	public function getInvite(string $code): ?GuildInvite {
		$data = RestAPIHandler::getInstance()->getInvite($code);
		if ($data->isFailed() or !is_array(($value = @json_decode($data->getRawData(), true)))) return null;
		return GuildSettingsInitializer::createInvitation($value);
	}
	
	/**
	 * Deletes an Invitation by a specific code
	 *
	 * @api
	 *
	 * @param string $code
	 *
	 * @return bool
	 */
	public function deleteInvite(string $code): bool {
		$data = RestAPIHandler::getInstance()->deleteInvite($code);
		if ($data->isFailed() or !is_array(($value = @json_decode($data->getRawData(), true))) or !isset($value["code"]) or ($value["code"] !== $code)) return false;
		return true;
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
		
		Discord::getInstance()->toSend[] = $activity;
	}
}