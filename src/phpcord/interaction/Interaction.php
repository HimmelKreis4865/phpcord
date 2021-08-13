<?php

namespace phpcord\interaction;

use phpcord\channel\Sendable;
use phpcord\channel\TextMessage;
use phpcord\guild\GuildMember;
use phpcord\http\RestAPIHandler;
use phpcord\task\Promise;
use phpcord\user\User;
use phpcord\utils\MemberInitializer;
use function is_string;
use function json_decode;
use function var_dump;

class Interaction {
	
	public const TYPE_PING = 1;
	
	public const TYPE_COMMAND = 2;
	
	public const TYPE_MESSAGE_COMPONENT = 3;
	
	
	//public const RESPONSE_PONG = 1;
	
	public const RESPONSE_REPLY = 4;
	
	public const RESPONSE_DEFER_WITH_STATE = 5;
	
	// public const RESPONSE_DEFER = 6;
	
	// public const RESPONSE_UPDATE = 7;
	
	private function __construct(public string $token, public int $version, public int $type, public string $id, public string $applicationId, public string $channelId, public ?string $guildId, protected GuildMember|User $member, protected InteractionData $data) { }
	
	public static function fromArray(array $data): Interaction {
		$member = (isset($data["user"]) ? MemberInitializer::createUser($data["user"], "-") : MemberInitializer::createMember($data["member"], $data["guild_id"]));
		
		return new Interaction($data["token"], $data["version"], $data["type"], $data["id"], $data["application_id"],
			$data["channelId"] ?? $member->getId(), $data["guild_id"] ?? "-", $member,
			InteractionData::fromArray($data["guild_id"] ?? "-", $data["data"]));
	}
	
	public function defer(): Promise {
		return RestAPIHandler::getInstance()->sendImmediateInteractionReply($this->token, $this->id, self::RESPONSE_DEFER_WITH_STATE, []);
	}
	
	public function reply(Sendable $sendable, bool $private = false): Promise {
		$d = (json_decode($sendable->getFormattedData(), true) + ["flags" => ($private ? (1 << 6) : 0)]);
		if (isset($d["embed"])) $d["embeds"] = [$d["embed"]]; unset($d["embed"]);
		return RestAPIHandler::getInstance()->sendImmediateInteractionReply($this->token, $this->id, self::RESPONSE_REPLY, $d);
	}
	
	public function send(Sendable $sendable, bool $private = false): Promise {
		$d = (json_decode($sendable->getFormattedData(), true) + ["flags" => ($private ? (1 << 6) : 0)]);
		if (isset($d["embed"])) $d["embeds"] = [$d["embed"]]; unset($d["embed"]);
		return RestAPIHandler::getInstance()->sendInteractionReply($this->token, $this->applicationId, self::RESPONSE_REPLY, $d);
	}
	
	public function edit(Sendable|string $sendable, bool $private = false): Promise {
		if (!$sendable instanceof Sendable) $sendable = new TextMessage($sendable);
		$d = json_decode($sendable->getFormattedData(), true);
		if (isset($d["embed"])) $d["embeds"] = [$d["embed"]]; unset($d["embed"]);
		return RestAPIHandler::getInstance()->editInteractionReply($this->token, $this->applicationId, self::RESPONSE_REPLY, $d);
	}
	
	public function fromDm(): bool {
		return ($this->guildId === null);
	}
	
	/**
	 * @return GuildMember|User
	 */
	public function getMember(): User|GuildMember {
		return $this->member;
	}
	
	/**
	 * @return InteractionData
	 */
	public function getData(): InteractionData {
		return $this->data;
	}
}