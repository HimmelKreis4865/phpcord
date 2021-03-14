<?php

namespace phpcord\http;

use phpcord\Discord;
use phpcord\utils\InstantiableTrait;
use function array_merge;
use function is_null;
use function json_encode;
use function urlencode;

final class RestAPIHandler {
	use InstantiableTrait;

	/** @var string $auth */
	private $auth;

	protected const API = "https://discord.com/api/v" . Discord::VERSION . "/";

	public function setAuth(string $token) {
		$this->auth = "Bot " . $token;
	}

	private function createRestResponse($data) : RestResponse {
		$response = new RestResponse($data);
		if ($data === false) return $response->fail();
		return $response;
	}

	public function getDefaultRequest(string $url, string $requestMethod = HTTPRequest::REQUEST_POST, bool $addContentType = true): HTTPRequest {
		$request = new HTTPRequest($url, $requestMethod);
		$request->addHeader("Authorization", $this->auth);
		if ($addContentType) $request->setContentType();
		return $request;
	}

	public function getChannel(string $channelId): RestResponse {
		$request = $this->getDefaultRequest(self::API . "channels/" . $channelId, HTTPRequest::REQUEST_GET);
		return $this->createRestResponse($request->submit());
	}

	public function sendMessage(string $channelId, string $data, string $contentType): RestResponse {
		$request = $this->getDefaultRequest(self::API . "channels/" . $channelId . "/messages", HTTPRequest::REQUEST_POST, false);
		$request->setContentType($contentType);
		$request->addHTTPData("content", $data);
		return $this->createRestResponse($request->submit());
	}

	public function sendReply(array $referenced_message, array $data): RestResponse {
		$request = $this->getDefaultRequest(self::API . "channels/" . $referenced_message["channel_id"] . "/messages");
		$request->addHTTPData("content", json_encode(array_merge($data, ["message_reference" => [ "message_id" => $referenced_message["message_id"], "channel_id" => $referenced_message["channel_id"], "guild_id" => $referenced_message["guild_id"] ]])));
		return $this->createRestResponse($request->submit());
	}

	public function deleteMessage(string $id, string $channelId): RestResponse {
		$request = $this->getDefaultRequest(self::API . "channels/" . $channelId . "/messages/" . $id, HTTPRequest::REQUEST_DELETE, false);
		return $this->createRestResponse($request->submit());
	}
	
	public function bulkDelete(string $channelId, array $messages) {
		$request = $this->getDefaultRequest(self::API . "channels/" . $channelId . "/messages/bulk-delete");
		$request->addHeader("messages", json_encode($messages));
		$request->addHTTPData("content", json_encode(["messages" => $messages]));
		return $this->createRestResponse($request->submit());
	}

	public function getAuditLog(string $guildId): RestResponse {
		$request = $this->getDefaultRequest(self::API . "guilds/" . $guildId . "/audit-logs", HTTPRequest::REQUEST_GET);
		return $this->createRestResponse($request->submit());
	}

	public function getMessages(string $id, int $limit): RestResponse {
		$request = $this->getDefaultRequest(self::API . "channels/" . $id . "/messages", HTTPRequest::REQUEST_GET);
		$request->addRawGet("limit", $limit);
		return $this->createRestResponse($request->submit());
	}
	
	public function getMessage(string $channelId, string $messageId): RestResponse {
		$request = $this->getDefaultRequest(self::API . "channels/" . $channelId . "/messages/" . $messageId, HTTPRequest::REQUEST_GET);
		return $this->createRestResponse($request->submit());
	}

	public function addBan(string $guildId, string $userId, ?string $reason = null, ?int $deleteMessageDays = null): RestResponse {
		$request = $this->getDefaultRequest(self::API . "guilds/" . $guildId . "/bans/" . $userId, HTTPRequest::REQUEST_PUT);
		$request->addHTTPData("content", json_encode(["reason" => $reason, "delete_message_days" => $deleteMessageDays]));
		return $this->createRestResponse($request->submit());
	}

	public function removeBan(string $guildId, string $userId): RestResponse {
		$request = $this->getDefaultRequest(self::API . "guilds/" . $guildId . "/bans/" . $userId, HTTPRequest::REQUEST_DELETE, false);
		return $this->createRestResponse($request->submit());
	}

	public function getBans(string $guildId): RestResponse {
		$request = $this->getDefaultRequest(self::API . "guilds/" . $guildId . "/bans", HTTPRequest::REQUEST_GET);
		return $this->createRestResponse($request->submit());
	}

	public function getWebhooksByChannel(string $id): RestResponse {
		$request = $this->getDefaultRequest(self::API . "channels/" . $id . "/webhooks", HTTPRequest::REQUEST_GET);
		return $this->createRestResponse($request->submit());
	}

	public function getWebhooksByGuild(string $id): RestResponse {
		$request = $this->getDefaultRequest(self::API . "guilds/" . $id . "/webhooks", HTTPRequest::REQUEST_GET);
		return $this->createRestResponse($request->submit());
	}

	public function createWebhook(string $channelId, string $name, ?string $avatar = null): RestResponse {
		$request = $this->getDefaultRequest(self::API . "channels/" . $channelId . "/webhooks");
		$request->addHTTPData("content", json_encode(["name" => $name, "avatar" => $avatar]));
		return $this->createRestResponse($request->submit());
	}
	
	public function deleteWebhook(string $id): RestResponse {
		$request = $this->getDefaultRequest(self::API . "webhooks/" . $id, HTTPRequest::REQUEST_DELETE, false);
		return $this->createRestResponse($request->submit());
	}
	
	public function modifyWebhook(string $id, ?string $name = null, ?string $new_channel = null, ?string $avatar = null): RestResponse {
		$request = $this->getDefaultRequest(self::API . "webhooks/" . $id, HTTPRequest::REQUEST_PATCH);
		$array = [];
		if (!is_null($new_channel)) $array["channel_id"] = $new_channel;
		if (!is_null($avatar)) $array["avatar"] = $avatar;
		if (!is_null($name)) $array["name"] = $name;
		$request->addHTTPData("content", json_encode($array));
		return $this->createRestResponse($request->submit());
	}
	
	public function getInvite(string $code, bool $withCount = true): RestResponse {
		$request = $this->getDefaultRequest(self::API . "invites/" . $code, HTTPRequest::REQUEST_GET);
		$request->addRawGet("with_count", $withCount);
		return $this->createRestResponse($request->submit());
	}
	
	public function deleteInvite(string $code): RestResponse {
		$request = $this->getDefaultRequest(self::API . "invites/" . $code, HTTPRequest::REQUEST_DELETE, false);
		return $this->createRestResponse($request->submit());
	}
	
	public function removeAllReactions(string $channelId, string $messageId): RestResponse {
		$request = $this->getDefaultRequest(self::API . "channels/" . $channelId . "/messages/" . $messageId . "/reactions", HTTPRequest::REQUEST_DELETE, false);
		return $this->createRestResponse($request->submit());
	}
	
	public function removeReactionId(string $channelId, string $messageId, string $emoji): RestResponse {
		$request = $this->getDefaultRequest(self::API . "channels/" . $channelId . "/messages/" . $messageId . "/reactions/" . urlencode($emoji), HTTPRequest::REQUEST_DELETE, false);
		return $this->createRestResponse($request->submit());
	}
	
	public function removeMyReaction(string $channelId, string $messageId, string $emoji): RestResponse {
		$request = $this->getDefaultRequest(self::API . "channels/" . $channelId . "/messages/" . $messageId . "/reactions/" . urlencode($emoji) . "/@me", HTTPRequest::REQUEST_DELETE, false);
		return $this->createRestResponse($request->submit());
	}
	
	public function removeUserReaction(string $channelId, string $messageId, string $userId, string $emoji): RestResponse {
		$request = $this->getDefaultRequest(self::API . "channels/" . $channelId . "/messages/" . $messageId . "/reactions/" . urlencode($emoji) . "/" . $userId, HTTPRequest::REQUEST_DELETE, false);
		return $this->createRestResponse($request->submit());
	}
	
	public function createReaction(string $channelId, string $messageId, string $emoji): RestResponse {
		$request = $this->getDefaultRequest(self::API . "channels/" . $channelId . "/messages/" . $messageId . "/reactions/" . urlencode($emoji) . "/@me", HTTPRequest::REQUEST_PUT);
		// workaround to prevent length required error
		$request->addHTTPData("content", json_encode([]));
		return $this->createRestResponse($request->submit());
	}
	
	public function getReactions(string $channelId, string $messageId, string $emoji, int $limit = 100): RestResponse {
		$request = $this->getDefaultRequest(self::API . "channels/" . $channelId . "/messages/" . $messageId . "/reactions/" . urlencode($emoji), HTTPRequest::REQUEST_GET);
		$request->addRawGet("limit", $limit);
		return $this->createRestResponse($request->submit());
	}
	
	public function createChannel(string $guildID, array $data): RestResponse {
		$request = $this->getDefaultRequest(self::API . "guilds/" . $guildID . "/channels");
		$request->addHTTPData("content", json_encode($data));
		return $this->createRestResponse($request->submit());
	}
	
	public function deleteChannel(string $id): RestResponse {
		$request = $this->getDefaultRequest(self::API . "channels/" . $id, HTTPRequest::REQUEST_DELETE, false);
		return $this->createRestResponse($request->submit());
	}
	
	public function updateChannel(string $id, array $data): RestResponse {
		$request = $this->getDefaultRequest(self::API . "channels/" . $id, HTTPRequest::REQUEST_PATCH);
		$request->addHTTPData("content", json_encode($data));
		return $this->createRestResponse($request->submit());
	}
	public function createRole(string $guildId, string $name, int $color, string $permissions, bool $hoist = false, bool $mentionable = false): RestResponse {
		$request = $this->getDefaultRequest(self::API . "guilds/" . $guildId . "/roles");
		$request->addHTTPData("content", json_encode(["name" => $name, "color" => $color, "permissions" => $permissions, "hoist" => $hoist, "mentionable" => $mentionable]));
		return $this->createRestResponse($request->submit());
	}
	
	public function modifyRole(string $guildId, string $id,  string $name, int $color, int $permissions, bool $hoist = false, bool $mentionable = false): RestResponse {
		$request = $this->getDefaultRequest(self::API . "guilds/" . $guildId . "/roles/" . $id, HTTPRequest::REQUEST_PATCH);
		$request->addHTTPData("content", json_encode(["name" => $name, "color" => $color, "permissions" => $permissions, "hoist" => $hoist, "mentionable" => $mentionable]));
		return $this->createRestResponse($request->submit());
	}
	
	public function deleteRole(string $guildId, string $id): RestResponse {
		$request = $this->getDefaultRequest(self::API . "guilds/" . $guildId . "/roles/" . $id, HTTPRequest::REQUEST_DELETE, false);
		return $this->createRestResponse($request->submit());
	}
	
	public function removeMember(string $guildId, string $id, ?string $reason = null): RestResponse {
		$request = $this->getDefaultRequest(self::API . "guilds/" . $guildId . "/members/" . $id, HTTPRequest::REQUEST_DELETE, false);
		if ($reason !== null) $request->addRawGet("reason", $reason);
		return $this->createRestResponse($request->submit());
	}
	
	public function updateMember(string $guildId, string $id, array $data): RestResponse {
		$request = $this->getDefaultRequest(self::API . "guilds/" . $guildId . "/members/" . $id, HTTPRequest::REQUEST_PATCH);
		$request->addHTTPData("content", json_encode($data));
		return $this->createRestResponse($request->submit());
	}
	
	public function setBotNick(string $guildId, string $nick): RestResponse {
		$request = $this->getDefaultRequest(self::API . "guilds/" . $guildId . "/members/@me/nick", HTTPRequest::REQUEST_PATCH);
		$request->addHTTPData("content", json_encode(["nick" => $nick]));
		return $this->createRestResponse($request->submit());
	}
	
	public function addRoleToUser(string $guildId, string $user, string $role): RestResponse {
		$request = $this->getDefaultRequest(self::API . "guilds/" . $guildId . "/members/" . $user . "/roles/" . $role, HTTPRequest::REQUEST_PUT);
		$request->addHTTPData("content", json_encode([]));
		return $this->createRestResponse($request->submit());
	}
	
	public function removeRoleFromUser(string $guildId, string $user, string $role): RestResponse {
		$request = $this->getDefaultRequest(self::API . "guilds/" . $guildId . "/members/" . $user . "/roles/" . $role, HTTPRequest::REQUEST_DELETE, false);
		return $this->createRestResponse($request->submit());
	}
	
	public function setChannelPosition(string $guildId, string $channelId, int $position): RestResponse {
		$request = $this->getDefaultRequest(self::API . "guilds/" . $guildId . "/channels", HTTPRequest::REQUEST_PATCH);
		$request->addHTTPData("content", json_encode(["id" => $channelId, "position" => $position]));
		return $this->createRestResponse($request->submit());
	}
	
	public function triggerTyping(string $channelId): RestResponse {
		$request = $this->getDefaultRequest(self::API . "channels/" . $channelId . "/typing");
		$request->addHTTPData("content", json_encode([]));
		return $this->createRestResponse($request->submit());
	}
	
	public function pinMessage(string $channelId, string $messageId): RestResponse {
		$request = $this->getDefaultRequest(self::API . "channels/" . $channelId . "/pins/" . $messageId, HTTPRequest::REQUEST_PUT);
		$request->addHTTPData("content", json_encode([]));
		return $this->createRestResponse($request->submit());
	}
	
	public function unpinMessage(string $channelId, string $messageId): RestResponse {
		$request = $this->getDefaultRequest(self::API . "channels/" . $channelId . "/pins/" . $messageId, HTTPRequest::REQUEST_DELETE, false);
		return $this->createRestResponse($request->submit());
	}
	
	public function getPins(string $channelId): RestResponse {
		$request = $this->getDefaultRequest(self::API . "channels/" . $channelId . "/pins", HTTPRequest::REQUEST_GET);
		return $this->createRestResponse($request->submit());
	}
	
	public function followChannel(string $channelToFollow, string $targetId): RestResponse {
		$request = $this->getDefaultRequest(self::API . "channels/" . $channelToFollow . "/followers");
		$request->addHTTPData("content", json_encode(["webhook_channel_id" => $targetId]));
		return $this->createRestResponse($request->submit());
	}
	
	public function createInvite(string $channelId, int $duration, int $max_uses, bool $temporary_membership = false, bool $unique = false, ?string $target_user = null): RestResponse {
		$request = $this->getDefaultRequest(self::API . "channels/" . $channelId . "/invites");
		$request->addHTTPData("content", json_encode(["max_age" => $duration, "max_uses" => $max_uses, "temporary" => $temporary_membership, "unique" => $unique, "target_user" => $target_user]));
		return $this->createRestResponse($request->submit());
	}
	
	public function getInvitesByChannel(string $channelId): RestResponse {
		$request = $this->getDefaultRequest(self::API . "channels/" . $channelId . "/invites", HTTPRequest::REQUEST_GET);
		return $this->createRestResponse($request->submit());
	}
	
	public function getGuildInvites(string $guildId): RestResponse {
		$request = $this->getDefaultRequest(self::API . "guilds/" . $guildId . "/invites", HTTPRequest::REQUEST_GET);
		return $this->createRestResponse($request->submit());
	}
	
	public function registerSlashCommand(string $guildId, string $applicationId, array $data): RestResponse {
		$request = $this->getDefaultRequest(self::API . "applications/" . $applicationId . "/guilds/" . $guildId . "/commands");
		$request->addHTTPData("content", json_encode($data));
		return $this->createRestResponse($request->submit());
	}
	
	public function registerGlobalSlashCommand(string $applicationId, array $data): RestResponse {
		$request = $this->getDefaultRequest(self::API . "applications/" . $applicationId);
		$request->addHTTPData("content", json_encode($data));
		return $this->createRestResponse($request->submit());
	}
	
	public function getSlashCommands(string $applicationId): RestResponse {
		$request = $this->getDefaultRequest(self::API . "applications/" . $applicationId . "/commands", HTTPRequest::REQUEST_GET);
		return $this->createRestResponse($request->submit());
	}
	
	public function createDM(string $id): RestResponse {
		$request = $this->getDefaultRequest(self::API . "users/@me/channels");
		$request->addHTTPData("content", json_encode(["recipient_id" => $id]));
		return $this->createRestResponse($request->submit());
	}
	
	public function editMessage(string $channelId, string $id, array $data): RestResponse {
		$request = $this->getDefaultRequest(self::API . "channels/" . $channelId . "/messages/" . $id, HTTPRequest::REQUEST_PATCH);
		$request->addHTTPData("content", json_encode($data));
		return $this->createRestResponse($request->submit());
	}
}