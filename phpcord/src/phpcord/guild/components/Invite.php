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

namespace phpcord\guild\components;

use phpcord\async\completable\Completable;
use phpcord\guild\PartialGuild;
use phpcord\http\RestAPI;
use phpcord\user\User;
use phpcord\utils\Timestamp;
use phpcord\utils\Utils;

class Invite {
	
	/**
	 * @param string $code
	 * @param PartialGuild|null $guild
	 * @param User|null $inviter
	 * @param Timestamp|null $expiration
	 * @param int|null $memberCount
	 * @param int|null $onlineCount
	 * @param InviteMetadata|null $metadata
	 */
	public function __construct(private string $code, private ?PartialGuild $guild, private ?User $inviter, private ?Timestamp $expiration, private ?int $memberCount, private ?int $onlineCount, private ?InviteMetadata $metadata) { }
	
	/**
	 * @return string
	 */
	public function getCode(): string {
		return $this->code;
	}
	
	/**
	 * @return PartialGuild|null
	 */
	public function getGuild(): ?PartialGuild {
		return $this->guild;
	}
	
	/**
	 * @return User|null
	 */
	public function getInviter(): ?User {
		return $this->inviter;
	}
	
	/**
	 * Only returned when getting invite with withExpiration option enabled (default)
	 *
	 * @return Timestamp|null
	 */
	public function getExpiration(): ?Timestamp {
		return $this->expiration;
	}
	
	/**
	 * @return InviteMetadata|null
	 */
	public function getMetadata(): ?InviteMetadata {
		return $this->metadata;
	}
	
	/**
	 * Only returned when getting invite with withCounts option enabled (default)
	 * Not returned when fetching from a guild
	 *
	 * @return int|null
	 */
	public function getMemberCount(): ?int {
		return $this->memberCount;
	}
	
	/**
	 * Only returned when getting invite with withCounts option enabled (default)
	 * Not returned when fetching from a guild
	 *
	 * @return int|null
	 */
	public function getOnlineCount(): ?int {
		return $this->onlineCount;
	}
	
	/**
	 * @param string|null $reason
	 *
	 * @return Completable
	 */
	public function delete(string $reason = null): Completable {
		return RestAPI::getInstance()->deleteInvite($this->getCode(), $reason);
	}
	
	public static function fromArray(array $array): ?Invite {
		if (!Utils::contains($array, 'code')) return null;
		return new Invite($array['code'], (@$array['guild'] ? PartialGuild::fromArray($array['guild']) : null), (@$array['inviter'] ? User::fromArray($array['inviter']) : null), (@$array['expires_at'] ? Timestamp::fromDate($array['expires_at']) : null), @$array['member_count'], @$array['presence_count'], InviteMetadata::fromArray($array));
	}
}