<?php

namespace phpcord\http;

use phpcord\channel\Channel;
use phpcord\Discord;
use phpcord\guild\AuditLog;
use phpcord\guild\GuildChannel;
use phpcord\guild\MessageSentPromise;
use phpcord\guild\store\GuildStoredMessage;
use phpcord\utils\AuditLogInitializer;
use phpcord\utils\ChannelInitializer;
use phpcord\utils\InstantiableTrait;
use phpcord\utils\MainLogger;
use phpcord\utils\MessageInitializer;
use Promise\Processors\Rejecter;
use Promise\Processors\Resolver;
use Promise\Promise;
use function array_merge;
use function http_response_code;
use function is_null;
use function json_decode;
use function json_encode;
use function serialize;
use function unserialize;
use function urlencode;
use function var_dump;

final class RestAPIHandler {
	use InstantiableTrait;

	/** @var string $auth */
	private $auth;

	protected const API = "https://discord.com/api/v" . Discord::VERSION . "/";

	public function setAuth(string $token) {
		$this->auth = "Bot " . $token;
	}

	private function createRestResponse(HTTPRequest $request, callable $parser = null) : Promise {
		return new Promise(function (Resolver $resolver, Rejecter $rejecter, HTTPRequest $request, callable $parser): void {
			Discord::registerAutoload();
			$request->ignoreErrors();
			$result = $request->submit();
			var_dump($result);
			if (strval(($code = HTTPRequest::getResponseCode($result[1][0])))[0] != "2") {
				MainLogger::logWarning("Request failure: {$request->url} answered with " . ($code));
				$rejecter(new RequestFailure($code, $result[0]));
				return;
			}
			$res = $parser($result[0]);
			if ($res === null) {
				$resolver();
				return;
			}
			$resolver($res);
		}, $request, $parser);
	}

	public function getDefaultRequest(string $url, string $requestMethod = HTTPRequest::REQUEST_POST, bool $addContentType = true): HTTPRequest {
		$request = new HTTPRequest($url, $requestMethod);
		$request->addHeader("Authorization", $this->auth);
		if ($addContentType) $request->setContentType();
		return $request;
	}

	public function getChannel(string $channelId): Promise {
		$request = $this->getDefaultRequest(self::API . "channels/" . $channelId, HTTPRequest::REQUEST_GET);
		return $this->createRestResponse($request, function (string $content): GuildChannel {
			return ChannelInitializer::createChannel(($data = json_decode($content, true)), $data["guild_id"] ?? "-");
		});
	}

	public function sendMessage(string $guildId, string $channelId, string $data, string $contentType): Promise {
		$request = $this->getDefaultRequest(self::API . "channels/" . $channelId . "/messages", HTTPRequest::REQUEST_POST, false);
		$request->setContentType($contentType);
		$request->addHTTPData("content", $data);
		return $this->createRestResponse($request, function (string $content) use ($guildId) : MessageSentPromise {
			return new MessageSentPromise(MessageInitializer::fromStore($guildId, json_decode($content, true)));
		});
	}

	public function sendReply(array $referenced_message, array $data): Promise {
		$request = $this->getDefaultRequest(self::API . "channels/" . $referenced_message["channel_id"] . "/messages");
		$request->addHTTPData("content", json_encode(array_merge($data, ["message_reference" => [ "message_id" => $referenced_message["message_id"], "channel_id" => $referenced_message["channel_id"], "guild_id" => $referenced_message["guild_id"] ]])));
		$d = serialize($referenced_message);
		return $this->createRestResponse($request, function (string $content) use ($d) : MessageSentPromise {
			$d = unserialize($d);
			return new MessageSentPromise(MessageInitializer::fromStore($d["guild_id"], json_decode($content, true)));
		});
	}

	public function deleteMessage(string $id, string $channelId): Promise {
		$request = $this->getDefaultRequest(self::API . "channels/" . $channelId . "/messages/" . $id, HTTPRequest::REQUEST_DELETE, false);
		return $this->createRestResponse($request, function (string $content) : void { });
	}
	
	public function bulkDelete(string $channelId, array $messages): Promise {
		$request = $this->getDefaultRequest(self::API . "channels/" . $channelId . "/messages/bulk-delete");
		$request->addHeader("messages", json_encode($messages));
		$request->addHTTPData("content", json_encode(["messages" => $messages]));
		return $this->createRestResponse($request, function (string $content) : void { });
	}

	public function getAuditLog(string $guildId): Promise {
		$request = $this->getDefaultRequest(self::API . "guilds/" . $guildId . "/audit-logs", HTTPRequest::REQUEST_GET);
		return $this->createRestResponse($request, function (string $content) use ($guildId) : AuditLog {
			return AuditLogInitializer::create($guildId, json_decode($content, true) ?? []);
		});
	}

	public function getMessages(string $id, string $guildId, int $limit): Promise {
		$request = $this->getDefaultRequest(self::API . "channels/" . $id . "/messages", HTTPRequest::REQUEST_GET);
		$request->addRawGet("limit", $limit);
		return $this->createRestResponse($request, function (string $content) use ($guildId) : array {
			$response = json_decode($content, true);
			$messages = [];
			
			foreach ($response as $value) {
				$msg = MessageInitializer::fromStore($guildId, $value);
				$messages[$msg->id] = $msg;
			}
			
			return $messages;
		});
	}
	
	public function getMessage(string $channelId, string $guildId, string $messageId): Promise {
		$request = $this->getDefaultRequest(self::API . "channels/" . $channelId . "/messages/" . $messageId, HTTPRequest::REQUEST_GET);
		return $this->createRestResponse($request, function (string $content) use ($guildId) : GuildStoredMessage {
			return MessageInitializer::fromStore($guildId, json_decode($content, true) ?? []);
		});
	}

	public function addBan(string $guildId, string $userId, ?string $reason = null, ?int $deleteMessageDays = null): Promise {
		$request = $this->getDefaultRequest(self::API . "guilds/" . $guildId . "/bans/" . $userId, HTTPRequest::REQUEST_PUT);
		$request->addHTTPData("content", json_encode(["reason" => $reason, "delete_message_days" => $deleteMessageDays]));
		return $this->createRestResponse($request->submit());
	}

	public function removeBan(string $guildId, string $userId): Promise {
		$request = $this->getDefaultRequest(self::API . "guilds/" . $guildId . "/bans/" . $userId, HTTPRequest::REQUEST_DELETE, false);
		return $this->createRestResponse($request->submit());
	}

	public function getBans(string $guildId): Promise {
		$request = $this->getDefaultRequest(self::API . "guilds/" . $guildId . "/bans", HTTPRequest::REQUEST_GET);
		return $this->createRestResponse($request->submit());
	}

	public function getWebhooksByChannel(string $id): Promise {
		$request = $this->getDefaultRequest(self::API . "channels/" . $id . "/webhooks", HTTPRequest::REQUEST_GET);
		return $this->createRestResponse($request->submit());
	}

	public function getWebhooksByGuild(string $id): Promise {
		$request = $this->getDefaultRequest(self::API . "guilds/" . $id . "/webhooks", HTTPRequest::REQUEST_GET);
		return $this->createRestResponse($request->submit());
	}

	public function createWebhook(string $channelId, string $name, ?string $avatar = null): Promise {
		$request = $this->getDefaultRequest(self::API . "channels/" . $channelId . "/webhooks");
		$request->addHTTPData("content", json_encode(["name" => $name, "avatar" => $avatar]));
		return $this->createRestResponse($request->submit());
	}
	
	public function deleteWebhook(string $id): Promise {
		$request = $this->getDefaultRequest(self::API . "webhooks/" . $id, HTTPRequest::REQUEST_DELETE, false);
		return $this->createRestResponse($request->submit());
	}
	
	public function modifyWebhook(string $id, ?string $name = null, ?string $new_channel = null, ?string $avatar = null): Promise {
		$request = $this->getDefaultRequest(self::API . "webhooks/" . $id, HTTPRequest::REQUEST_PATCH);
		$array = [];
		if (!is_null($new_channel)) $array["channel_id"] = $new_channel;
		if (!is_null($avatar)) $array["avatar"] = $avatar;
		if (!is_null($name)) $array["name"] = $name;
		$request->addHTTPData("content", json_encode($array));
		return $this->createRestResponse($request->submit());
	}
	
	public function getInvite(string $code, bool $withCount = true): Promise {
		$request = $this->getDefaultRequest(self::API . "invites/" . $code, HTTPRequest::REQUEST_GET);
		$request->addRawGet("with_count", $withCount);
		return $this->createRestResponse($request->submit());
	}
	
	public function deleteInvite(string $code): Promise {
		$request = $this->getDefaultRequest(self::API . "invites/" . $code, HTTPRequest::REQUEST_DELETE, false);
		return $this->createRestResponse($request->submit());
	}
	
	public function removeAllReactions(string $channelId, string $messageId): Promise {
		$request = $this->getDefaultRequest(self::API . "channels/" . $channelId . "/messages/" . $messageId . "/reactions", HTTPRequest::REQUEST_DELETE, false);
		return $this->createRestResponse($request->submit());
	}
	
	public function removeReactionId(string $channelId, string $messageId, string $emoji): Promise {
		$request = $this->getDefaultRequest(self::API . "channels/" . $channelId . "/messages/" . $messageId . "/reactions/" . urlencode($emoji), HTTPRequest::REQUEST_DELETE, false);
		return $this->createRestResponse($request->submit());
	}
	
	public function removeMyReaction(string $channelId, string $messageId, string $emoji): Promise {
		$request = $this->getDefaultRequest(self::API . "channels/" . $channelId . "/messages/" . $messageId . "/reactions/" . urlencode($emoji) . "/@me", HTTPRequest::REQUEST_DELETE, false);
		return $this->createRestResponse($request->submit());
	}
	
	public function removeUserReaction(string $channelId, string $messageId, string $userId, string $emoji): Promise {
		$request = $this->getDefaultRequest(self::API . "channels/" . $channelId . "/messages/" . $messageId . "/reactions/" . urlencode($emoji) . "/" . $userId, HTTPRequest::REQUEST_DELETE, false);
		return $this->createRestResponse($request->submit());
	}
	
	public function createReaction(string $channelId, string $messageId, string $emoji): Promise {
		$request = $this->getDefaultRequest(self::API . "channels/" . $channelId . "/messages/" . $messageId . "/reactions/" . urlencode($emoji) . "/@me", HTTPRequest::REQUEST_PUT);
		// workaround to prevent length required error
		$request->addHTTPData("content", json_encode([]));
		return $this->createRestResponse($request->submit());
	}
	
	public function getReactions(string $channelId, string $messageId, string $emoji, int $limit = 100): Promise {
		$request = $this->getDefaultRequest(self::API . "channels/" . $channelId . "/messages/" . $messageId . "/reactions/" . urlencode($emoji), HTTPRequest::REQUEST_GET);
		$request->addRawGet("limit", $limit);
		return $this->createRestResponse($request->submit());
	}
	
	public function createChannel(string $guildID, array $data): Promise {
		$request = $this->getDefaultRequest(self::API . "guilds/" . $guildID . "/channels");
		$request->addHTTPData("content", json_encode($data));
		return $this->createRestResponse($request->submit());
	}
	
	public function deleteChannel(string $id): Promise {
		$request = $this->getDefaultRequest(self::API . "channels/" . $id, HTTPRequest::REQUEST_DELETE, false);
		return $this->createRestResponse($request->submit());
	}
	
	public function updateChannel(string $id, array $data): Promise {
		$request = $this->getDefaultRequest(self::API . "channels/" . $id, HTTPRequest::REQUEST_PATCH);
		$request->addHTTPData("content", json_encode($data));
		return $this->createRestResponse($request->submit());
	}
	public function createRole(string $guildId, string $name, int $color, string $permissions, bool $hoist = false, bool $mentionable = false): Promise {
		$request = $this->getDefaultRequest(self::API . "guilds/" . $guildId . "/roles");
		$request->addHTTPData("content", json_encode(["name" => $name, "color" => $color, "permissions" => $permissions, "hoist" => $hoist, "mentionable" => $mentionable]));
		return $this->createRestResponse($request->submit());
	}
	
	public function modifyRole(string $guildId, string $id,  string $name, int $color, int $permissions, bool $hoist = false, bool $mentionable = false): Promise {
		$request = $this->getDefaultRequest(self::API . "guilds/" . $guildId . "/roles/" . $id, HTTPRequest::REQUEST_PATCH);
		$request->addHTTPData("content", json_encode(["name" => $name, "color" => $color, "permissions" => $permissions, "hoist" => $hoist, "mentionable" => $mentionable]));
		return $this->createRestResponse($request->submit());
	}
	
	public function deleteRole(string $guildId, string $id): Promise {
		$request = $this->getDefaultRequest(self::API . "guilds/" . $guildId . "/roles/" . $id, HTTPRequest::REQUEST_DELETE, false);
		return $this->createRestResponse($request->submit());
	}
	
	public function removeMember(string $guildId, string $id, ?string $reason = null): Promise {
		$request = $this->getDefaultRequest(self::API . "guilds/" . $guildId . "/members/" . $id, HTTPRequest::REQUEST_DELETE, false);
		if ($reason !== null) $request->addRawGet("reason", $reason);
		return $this->createRestResponse($request->submit());
	}
	
	public function updateMember(string $guildId, string $id, array $data): Promise {
		$request = $this->getDefaultRequest(self::API . "guilds/" . $guildId . "/members/" . $id, HTTPRequest::REQUEST_PATCH);
		$request->addHTTPData("content", json_encode($data));
		return $this->createRestResponse($request->submit());
	}
	
	public function setBotNick(string $guildId, string $nick): Promise {
		$request = $this->getDefaultRequest(self::API . "guilds/" . $guildId . "/members/@me/nick", HTTPRequest::REQUEST_PATCH);
		$request->addHTTPData("content", json_encode(["nick" => $nick]));
		return $this->createRestResponse($request->submit());
	}
	
	public function addRoleToUser(string $guildId, string $user, string $role): Promise {
		$request = $this->getDefaultRequest(self::API . "guilds/" . $guildId . "/members/" . $user . "/roles/" . $role, HTTPRequest::REQUEST_PUT);
		$request->addHTTPData("content", json_encode([]));
		return $this->createRestResponse($request->submit());
	}
	
	public function removeRoleFromUser(string $guildId, string $user, string $role): Promise {
		$request = $this->getDefaultRequest(self::API . "guilds/" . $guildId . "/members/" . $user . "/roles/" . $role, HTTPRequest::REQUEST_DELETE, false);
		return $this->createRestResponse($request->submit());
	}
	
	public function setChannelPosition(string $guildId, string $channelId, int $position): Promise {
		$request = $this->getDefaultRequest(self::API . "guilds/" . $guildId . "/channels", HTTPRequest::REQUEST_PATCH);
		$request->addHTTPData("content", json_encode(["id" => $channelId, "position" => $position]));
		return $this->createRestResponse($request->submit());
	}
	
	public function triggerTyping(string $channelId): Promise {
		$request = $this->getDefaultRequest(self::API . "channels/" . $channelId . "/typing");
		$request->addHTTPData("content", json_encode([]));
		return $this->createRestResponse($request->submit());
	}
	
	public function pinMessage(string $channelId, string $messageId): Promise {
		$request = $this->getDefaultRequest(self::API . "channels/" . $channelId . "/pins/" . $messageId, HTTPRequest::REQUEST_PUT);
		$request->addHTTPData("content", json_encode([]));
		return $this->createRestResponse($request->submit());
	}
	
	public function unpinMessage(string $channelId, string $messageId): Promise {
		$request = $this->getDefaultRequest(self::API . "channels/" . $channelId . "/pins/" . $messageId, HTTPRequest::REQUEST_DELETE, false);
		return $this->createRestResponse($request->submit());
	}
	
	public function getPins(string $channelId): Promise {
		$request = $this->getDefaultRequest(self::API . "channels/" . $channelId . "/pins", HTTPRequest::REQUEST_GET);
		return $this->createRestResponse($request->submit());
	}
	
	public function followChannel(string $channelToFollow, string $targetId): Promise {
		$request = $this->getDefaultRequest(self::API . "channels/" . $channelToFollow . "/followers");
		$request->addHTTPData("content", json_encode(["webhook_channel_id" => $targetId]));
		return $this->createRestResponse($request->submit());
	}
	
	public function crosspostMessage(string $channel, string $message): Promise {
		$request = $this->getDefaultRequest(self::API . "channels/" . $channel . "/messages/" . $message . "/crosspost");
		$request->addHTTPData("content", json_encode([]));
		return $this->createRestResponse($request->submit());
	}
	
	public function createInvite(string $channelId, int $duration, int $max_uses, bool $temporary_membership = false, bool $unique = false, ?string $target_user = null): Promise {
		$request = $this->getDefaultRequest(self::API . "channels/" . $channelId . "/invites");
		$request->addHTTPData("content", json_encode(["max_age" => $duration, "max_uses" => $max_uses, "temporary" => $temporary_membership, "unique" => $unique, "target_user" => $target_user]));
		return $this->createRestResponse($request->submit());
	}
	
	public function getInvitesByChannel(string $channelId): Promise {
		$request = $this->getDefaultRequest(self::API . "channels/" . $channelId . "/invites", HTTPRequest::REQUEST_GET);
		return $this->createRestResponse($request->submit());
	}
	
	public function getGuildInvites(string $guildId): Promise {
		$request = $this->getDefaultRequest(self::API . "guilds/" . $guildId . "/invites", HTTPRequest::REQUEST_GET);
		return $this->createRestResponse($request->submit());
	}
	
	public function registerSlashCommand(string $guildId, string $applicationId, array $data): Promise {
		$request = $this->getDefaultRequest(self::API . "applications/" . $applicationId . "/guilds/" . $guildId . "/commands");
		$request->addHTTPData("content", json_encode($data));
		return $this->createRestResponse($request->submit());
	}
	
	public function registerGlobalSlashCommand(string $applicationId, array $data): Promise {
		$request = $this->getDefaultRequest(self::API . "applications/" . $applicationId);
		$request->addHTTPData("content", json_encode($data));
		return $this->createRestResponse($request->submit());
	}
	
	public function getSlashCommands(string $applicationId): Promise {
		$request = $this->getDefaultRequest(self::API . "applications/" . $applicationId . "/commands", HTTPRequest::REQUEST_GET);
		return $this->createRestResponse($request->submit());
	}
	
	public function createDM(string $id): Promise {
		$request = $this->getDefaultRequest(self::API . "users/@me/channels");
		$request->addHTTPData("content", json_encode(["recipient_id" => $id]));
		return $this->createRestResponse($request->submit());
	}
	
	public function editMessage(string $channelId, string $id, array $data): Promise {
		$request = $this->getDefaultRequest(self::API . "channels/" . $channelId . "/messages/" . $id, HTTPRequest::REQUEST_PATCH);
		$request->addHTTPData("content", json_encode($data));
		return $this->createRestResponse($request->submit());
	}
}