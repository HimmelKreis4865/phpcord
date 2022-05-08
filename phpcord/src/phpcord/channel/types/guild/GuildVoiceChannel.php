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

namespace phpcord\channel\types\guild;

use JetBrains\PhpStorm\ArrayShape;
use phpcord\async\completable\Completable;
use phpcord\channel\GuildChannel;
use phpcord\channel\helper\InvitableTrait;
use phpcord\channel\overwrite\PermissionOverwrite;
use phpcord\Discord;
use phpcord\runtime\network\Network;
use phpcord\runtime\network\packet\VoiceStateUpdatePacket;
use phpcord\utils\Utils;
use phpcord\voice\VoiceRequest;
use phpcord\voice\VoiceRequestPool;

class GuildVoiceChannel extends GuildChannel {
	use InvitableTrait;
	
	/**
	 * @param int $id
	 * @param int $guildId
	 * @param string $name
	 * @param int $position
	 * @param int|null $parentId
	 * @param PermissionOverwrite[] $overwrites
	 * @param int $bitrate
	 * @param int $userLimit
	 */
	public function __construct(int $id, int $guildId, string $name, int $position, ?int $parentId, array $overwrites, private int $bitrate, private int $userLimit = 0) {
		parent::__construct($id, $guildId, $name, $position, $parentId, $overwrites);
	}
	
	/**
	 * @return int
	 */
	public function getBitrate(): int {
		return $this->bitrate;
	}
	
	/**
	 * @return int
	 */
	public function getUserLimit(): int {
		return $this->userLimit;
	}
	
	public static function fromArray(array $array): ?self {
		if (!Utils::contains($array, 'id', 'guild_id', 'name', 'position', 'bitrate')) return null;
		return new GuildVoiceChannel($array['id'], $array['guild_id'], $array['name'], $array['position'], @$array['parent_id'], $array['permission_overwrites'] ?? [], $array['bitrate'], $array['user_limit'] ?? 0);
	}
	
	/*
	 * note: DO NOT TRY TO USE ANYTHING RELATED TO VOICE RIGHT NOW
	 * todo: fix this functionality
	public function join(bool $mute = false, bool $deaf = false): Completable {
		VoiceRequestPool::getInstance()->addRequest(new VoiceRequest($this->getGuildId(), Discord::getInstance()->getClient()->getUser()->getId()));
		Network::getInstance()->getGateway()->sendPacket(new VoiceStateUpdatePacket($this->getGuildId(), $this->getId(), $mute, $deaf));
		return Completable::sync();
	}
	*/
	
	public function replaceBy(GuildChannel $channel): void {
		if (!$channel instanceof GuildVoiceChannel) return;
		$this->bitrate = $channel->getBitrate();
		$this->userLimit = $channel->getUserLimit();
		parent::replaceBy($channel);
	}
	
	/**
	 * @param int $bitrate
	 *
	 * @return Completable<GuildVoiceChannel>
	 */
	public function setBitrate(int $bitrate): Completable {
		$this->bitrate = $bitrate;
		return $this->triggerUpdate();
	}
	
	/**
	 * @param int $userLimit
	 *
	 * @return Completable<GuildVoiceChannel>
	 */
	public function setUserLimit(int $userLimit): Completable {
		$this->userLimit = $userLimit;
		return $this->triggerUpdate();
	}
	
	#[ArrayShape(['bitrate' => "int", 'user_limit' => "int"])] public function getSerializationData(): array {
		return [
			'bitrate' => $this->bitrate,
			'user_limit' => $this->userLimit
		];
	}
}