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

namespace phpcord\interaction;

use phpcord\async\completable\Completable;
use phpcord\channel\TextChannel;
use phpcord\Discord;
use phpcord\exception\IndexNotFoundException;
use phpcord\guild\GuildMember;
use phpcord\http\RestAPI;
use phpcord\interaction\component\Modal;
use phpcord\message\Message;
use phpcord\message\MessageFlags;
use phpcord\message\Sendable;
use phpcord\message\sendable\MessageBuilder;
use phpcord\user\User;
use phpcord\utils\Utils;
use RuntimeException;
use function json_encode;

class Interaction {
	
	private const CURRENT_VERSION = 1;
	
	private bool $responded = false;
	
	/**
	 * @param int $id
	 * @param int $applicationId
	 * @param InteractionData|null $data
	 * @param int|null $guildId
	 * @param int|null $channelId
	 * @param GuildMember|null $member
	 * @param User|null $user
	 * @param string $token
	 * @param int $version
	 * @param Message|null $message
	 * @param string|null $locale
	 * @param string|null $guildLocale
	 */
	public function __construct(private int $id, private int $applicationId, private ?InteractionData $data, private ?int $guildId, private ?int $channelId, private ?GuildMember $member, private ?User $user, private string $token, private int $version, private ?Message $message, private ?string $locale, private ?string $guildLocale) { }
	
	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
	}
	
	/**
	 * @return int
	 */
	public function getApplicationId(): int {
		return $this->applicationId;
	}
	
	/**
	 * @return InteractionData|null
	 */
	public function getData(): ?InteractionData {
		return $this->data;
	}
	
	/**
	 * @return int|null
	 */
	public function getGuildId(): ?int {
		return $this->guildId;
	}
	
	/**
	 * @return int|null
	 */
	public function getChannelId(): ?int {
		return $this->channelId;
	}
	
	/**
	 * @return Completable<TextChannel>
	 */
	public function getChannel(): Completable {
		if ($this->guildId)
			return (Discord::getInstance()->getClient()->getGuilds()->get($this->guildId)?->getChannel($this->channelId) ?? Completable::fail(new IndexNotFoundException('Guild ' . $this->guildId . ' does not exist!')));
		return Discord::getInstance()->getClient()->getChannel($this->channelId);
	}
	
	/**
	 * @return string
	 */
	public function getToken(): string {
		return $this->token;
	}
	
	/**
	 * @return GuildMember|null
	 */
	public function getMember(): ?GuildMember {
		return $this->member;
	}
	
	/**
	 * @return Message|null
	 */
	public function getMessage(): ?Message {
		return $this->message;
	}
	
	/**
	 * @return User|null
	 */
	public function getUser(): ?User {
		return $this->user;
	}
	
	/**
	 * @return string|null
	 */
	public function getGuildLocale(): ?string {
		return $this->guildLocale;
	}
	
	/**
	 * @return string|null
	 */
	public function getLocale(): ?string {
		return $this->locale;
	}
	
	/**
	 * @return int
	 */
	public function getVersion(): int {
		return $this->version;
	}
	
	/**
	 * (Message Component only)
	 * Will edit the original message of the component interacted
	 *
	 * @param Sendable $sendable
	 *
	 * @return Completable
	 */
	public function editOrigin(Sendable $sendable): Completable {
		return $this->respond($type = InteractionResponseTypes::UPDATE_MESSAGE(), $this->stringifySendable($sendable, $type), $sendable->getContentType());
	}
	
	/**
	 * @param Sendable $sendable
	 *
	 * @return Completable
	 */
	public function reply(Sendable $sendable): Completable {
		return $this->respond($type = InteractionResponseTypes::CHANNEL_MESSAGE_WITH_SOURCE(), $this->stringifySendable($sendable, $type), $sendable->getContentType());
	}
	
	/**
	 * @param bool $ephemeral if set to true, the message will only be displayed to the command sender
	 * @param bool $display
	 *
	 * @return Completable
	 */
	public function defer(bool $ephemeral = false, bool $display = true): Completable {
		return $this->respond(($display ? InteractionResponseTypes::DEFERRED_CHANNEL_MESSAGE_WITH_SOURCE() : InteractionResponseTypes::DEFERRED_UPDATE_MESSAGE()), json_encode(['flags' => ($ephemeral ? MessageFlags::EPHEMERAL() : 0)]));
	}
	
	/**
	 * Sends a modal form as response that will be handled on its own later (after submit)
	 *
	 * @param Modal $modal
	 *
	 * @return Completable
	 */
	public function sendModal(Modal $modal): Completable {
		return $this->respond(InteractionResponseTypes::MODAL(), json_encode($modal));
	}
	
	/**
	 * @param int $type
	 * @param string|null $data
	 * @param string $contentType
	 *
	 * @return Completable
	 */
	private function respond(int $type, ?string $data, string $contentType = 'application/json'): Completable {
		if ($this->responded)
			throw new RuntimeException("Interaction of type " . $this->data::class . " got already responded.");
		$this->responded = true;
		$requiresWrapping = ($contentType === 'application/json');
		return RestAPI::getInstance()->sendInteractionResponse($this->id, $this->token, ($requiresWrapping ? '{"type": ' . $type . ',"data":' . $data . '}' : $data), $contentType);
	}
	
	/**
	 * @param Sendable $sendable
	 *
	 * @return Completable
	 */
	public function editResponse(Sendable $sendable): Completable {
		return RestAPI::getInstance()->updateInteractionResponse($this->applicationId, $this->token, $sendable);
	}
	
	/**
	 * @return Completable
	 */
	public function deleteResponse(): Completable {
		return RestAPI::getInstance()->deleteInteractionResponse($this->applicationId, $this->token);
	}

	private function stringifySendable(Sendable $sendable, int $type): string {
		if ($sendable instanceof MessageBuilder and $sendable->getContentType() !== "application/json") {
			// contains files
			return $sendable->getBody(function (MessageBuilder $builder) use ($type): string {
				return json_encode([
					"type" => $type,
					"data" => $builder->jsonSerialize()
				]);
			});
		}
		return $sendable->getBody();
	}
	
	public static function fromArray(array $array): ?Interaction {
		if (!Utils::contains($array, 'id', 'application_id', 'token', 'type')) return null;
		return new Interaction($array['id'], $array['application_id'], (@$array['data'] ? TypeToInteractionDataMap::getInstance()->select($array['type'])::fromArray(($array['data'] + ['guild_id' => @$array['guild_id']])) : null), @$array['guild_id'], @$array['channel_id'], (@$array['member'] ? GuildMember::fromArray(($array['member'] + ['guild_id' => $array['guild_id']])) : null), (@$array['user'] ? User::fromArray($array['user']) : null), $array['token'], $array['version'] ?? self::CURRENT_VERSION, (@$array['message'] ? Message::fromArray(($array['message'] + ['guild_id' => @$array['guild_id'], 'member' => @$array['member']])) : null), @$array['locale'], @$array['guild_locale']);
	}
}