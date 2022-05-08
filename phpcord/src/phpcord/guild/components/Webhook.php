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

use BadMethodCallException;
use JetBrains\PhpStorm\Pure;
use JsonSerializable;
use phpcord\async\completable\Completable;
use phpcord\Discord;
use phpcord\guild\Guild;
use phpcord\http\RestAPI;
use phpcord\image\Icon;
use phpcord\image\ImageData;
use phpcord\message\Sendable;
use phpcord\utils\CDN;
use RuntimeException;

class Webhook implements JsonSerializable {
	
	private ?ImageData $tmpAvatarData = null;
	
	/**
	 * @param int $id
	 * @param int|null $guildId
	 * @param int|null $channelId
	 * @param string|null $name
	 * @param Icon|null $avatar
	 * @param string|null $token
	 * @param int|null $applicationId
	 * @param string|null $url
	 */
	public function __construct(private int $id, private ?int $guildId, private ?int $channelId, private ?string $name, private ?Icon $avatar, private ?string $token, private ?int $applicationId, private ?string $url) { }
	
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
	
	public function getGuild(): ?Guild {
		return Discord::getInstance()->getClient()->getGuilds()->get($this->getGuildId());
	}
	
	public function getChannel(): Completable {
		if (!$this->getChannelId()) return Completable::fail(new RuntimeException('No channel is set to the webhook.'));
		return Discord::getInstance()->getClient()->getChannel($this->getChannelId());
	}
	
	/**
	 * @return int|null
	 */
	public function getChannelId(): ?int {
		return $this->channelId;
	}
	
	/**
	 * @param int $channelId
	 * @param string|null $reason
	 */
	public function setChannelId(int $channelId, ?string $reason = null): void {
		$this->channelId = $channelId;
		$this->update($reason);
	}
	
	/**
	 * @return Icon|null
	 */
	public function getAvatar(): ?Icon {
		return $this->avatar;
	}
	
	/**
	 * @param ImageData|null $avatar
	 * @param string|null $reason
	 */
	public function setAvatar(?ImageData $avatar, ?string $reason = null): void {
		$this->tmpAvatarData = $avatar;
		$this->update($reason);
	}
	
	/**
	 * @return string|null
	 */
	public function getName(): ?string {
		return $this->name;
	}
	
	/**
	 * @param string $name
	 * @param string|null $reason
	 */
	public function setName(string $name, ?string $reason = null): void {
		$this->name = $name;
		$this->update($reason);
	}
	
	/**
	 * @return int|null
	 */
	public function getApplicationId(): ?int {
		return $this->applicationId;
	}
	
	/**
	 * @return string|null
	 */
	public function getToken(): ?string {
		return $this->token;
	}
	
	/**
	 * @return string|null
	 */
	public function getUrl(): ?string {
		return $this->url;
	}
	
	/**
	 * @param string|null $reason
	 *
	 * @return Completable
	 */
	protected function update(?string $reason = null): Completable {
		return RestAPI::getInstance()->updateWebhook($this->getId(), $this->jsonSerialize(), $reason);
	}
	
	/**
	 * @param string|null $reason
	 *
	 * @return Completable
	 */
	public function delete(?string $reason = null): Completable {
		return RestAPI::getInstance()->deleteWebhook($this->getId(), $reason);
	}
	
	#[Pure] public function jsonSerialize(): array {
		return ([
			'name' => $this->name,
			'channel_id' => $this->channelId
		] + ($this->tmpAvatarData ? [
			'avatar' => $this->tmpAvatarData->encode()
		] : []));
	}
	
	/**
	 * @param Sendable $sendable
	 * @param string|null $token
	 *
	 * @return Completable
	 */
	public function execute(Sendable $sendable, ?string $token = null): Completable {
		$token = $token ?? $this->getToken();
		if (!$token) throw new BadMethodCallException('Cannot execute a webhook with no valid token');
		return RestAPI::getInstance()->executeWebhook($this->getId(), $token, $sendable);
	}
	
	public static function fromArray(array $array): Webhook {
		return new Webhook($array['id'], @$array['guild_id'], @$array['channel_id'], @$array['name'], (@$array['avatar'] ? new Icon($array['avatar'], CDN::USER_AVATAR($array['id'], $array['avatar'])) : null), @$array['token'], @$array['application_id'], @$array['url']);
	}
}