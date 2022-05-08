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

namespace phpcord\http;

use JetBrains\PhpStorm\Pure;
use phpcord\async\completable\Completable;
use phpcord\async\net\Request;
use phpcord\async\net\Response;
use phpcord\channel\ChannelTypes;
use phpcord\Discord;
use phpcord\guild\auditlog\AuditLog;
use phpcord\guild\Ban;
use phpcord\guild\components\Invite;
use phpcord\guild\components\Webhook;
use phpcord\guild\GuildMember;
use phpcord\guild\permissible\Role;
use phpcord\image\ImageData;
use phpcord\interaction\slash\PartialSlashCommand;
use phpcord\message\Message;
use phpcord\message\PartialEmoji;
use phpcord\message\Sendable;
use phpcord\utils\Factory;
use phpcord\utils\SingletonTrait;
use phpcord\Version;
use function count;
use function http_build_query;
use function var_dump;

final class RestAPI {
	use SingletonTrait;
	
	private const URL_BASE = 'https://discord.com/api/v' . Version::GATEWAY_VERSION . '/';
	
	private function get(string $path, array $parameters = [], array $headers = []): Completable {
		return Request::get(self::URL_BASE . $path . (count($parameters) ? '?' . http_build_query($parameters) : ''), ($headers + [
			'Authorization' => $this->getToken(),
			'Content-Type' => 'application/json'
		]));
	}
	
	private function post(string $path, array|string $content, array $headers = []): Completable {
		return Request::post(self::URL_BASE . $path, $content, ($headers + [
				'Authorization' => $this->getToken(),
				'Content-Type' => 'application/json'
			]));
	}
	
	private function patch(string $path, array|string $content, array $headers = []): Completable {
		return Request::patch(self::URL_BASE . $path, $content, ($headers + [
				'Authorization' => $this->getToken(),
				'Content-Type' => 'application/json'
			]));
	}
	
	private function put(string $path, array|string $content, array $headers = []): Completable {
		return Request::put(self::URL_BASE . $path, $content, ($headers + [
				'Authorization' => $this->getToken(),
				'Content-Type' => 'application/json'
			]));
	}
	
	private function delete(string $path, array $parameters = [], array $headers = []): Completable {
		return Request::delete(self::URL_BASE . $path . (count($parameters) ? '?' . http_build_query($parameters) : ''), ($headers + [
				'Authorization' => $this->getToken(),
			]));
	}
	
	#[Pure] private function getToken(): string {
		return 'Bot ' . Discord::getInstance()->getToken();
	}
	
	public function getMember(int $guildId, int $id): Completable {
		return $this->get('guilds/' . $guildId . '/members/' . $id)->then(fn(Response $response) => GuildMember::fromArray($response->decode() + ['guild_id' => $guildId]));
	}
	
	public function getChannel(int $id): Completable {
		return $this->get('channels/' . $id)->then(fn(Response $response) => ChannelTypes::createObject(($payload = $response->decode())['type'], $payload));
	}
	
	public function getMessages(int $channelId, array $options): Completable {
		return $this->get('channels/' . $channelId . '/messages', $options)->then(fn(Response $response) => Factory::createMessageArray($response->decode()));
	}
	
	public function getMessage(int $channelId, int $id, ?int $guildId): Completable {
		return $this->get('channels/' . $channelId . '/messages/' . $id)->then(function(Response $response) use ($guildId): ?Message {
			return Message::fromArray(($response->decode() + ['guild_id' => $guildId]));
		});
	}
	
	public function sendMessage(int $channelId, Sendable $sendable): Completable {
		return $this->post('channels/' . $channelId . '/messages', $sendable->getBody(), ['Content-Type' => $sendable->getContentType()])->then(fn(Response $response) => Message::fromArray($response->decode()));
	}
	
	public function getVoiceRegions(): Completable {
		return $this->get('voice/regions')->then(fn(Response $response) => Factory::createRtcRegionArray($response->decode()));
	}
	
	public function sendInteractionResponse(int $interactionId, string $token, string $encoded, string $contentType = 'application/json'): Completable {
		return $this->post('interactions/' . $interactionId .  '/' . $token . '/callback', $encoded, ['Content-Type' => $contentType]);
	}
	
	public function updateInteractionResponse(int $applicationId, string $token, Sendable $sendable): Completable {
		return $this->patch('webhooks/' . $applicationId .  '/' . $token . '/messages/@original', $sendable->getBody(), ['Content-Type' => $sendable->getContentType()]);
	}
	
	public function deleteInteractionResponse(int $applicationId, string $token): Completable {
		return $this->delete('webhooks/' . $applicationId .  '/' . $token . '/messages/@original');
	}
	
	public function registerGuildSlashCommand(int $applicationId, string $data, int $guildId): Completable {
		return $this->post('applications/' . $applicationId . '/guilds/' . $guildId . '/commands', $data)->then(fn(Response $response) => PartialSlashCommand::fromArray($response->decode()));
	}
	
	public function registerGlobalSlashCommand(int $applicationId, string $data): Completable {
		return $this->post('applications/' . $applicationId . '/commands', $data)->then(fn(Response $response) => PartialSlashCommand::fromArray($response->decode()));
	}
	
	public function triggerTyping(int $channelId): Completable {
		return $this->post('channels/' . $channelId . '/typing','');
	}
	
	public function createRole(int $guildId, array $roleData): Completable {
		return $this->post('guilds/' . $guildId . '/roles', $roleData)->then(fn(Response $response) => Role::fromArray(($response->decode() + ['guild_id' => $guildId])));
	}
	
	public function setRolePosition(int $guildId, int $roleId, int $position): Completable {
		return $this->patch('guilds/' . $guildId . '/roles', [
			'id' => $roleId,
			'position' => $position
		])->then(fn(Response $response) => Factory::createRoleArray($guildId, $response->decode()));
	}
	
	public function deleteRole(int $guildId, int $roleId, string $reason = null): Completable {
		return $this->delete('guilds/' . $guildId . '/roles/' . $roleId, ($reason ? [
			'X-Audit-Log-Reason' => $reason
		] : []));
	}
	
	public function updateRole(int $guildId, int $roleId, array $data, string $reason = null): Completable {
		return $this->patch('guilds/' . $guildId . '/roles/' . $roleId, $data, ($reason ? [
			'X-Audit-Log-Reason' => $reason
		] : []));
	}
	
	public function createChannel(int $guildId, string $channelData): Completable {
		return $this->post('guilds/' . $guildId . '/channels', $channelData)->then(fn(Response $response) => ChannelTypes::createObject(null, ($response->decode() + ['guild_id' => $guildId])));
	}
	
	public function updateChannel(int $channelId, array $channelData): Completable {
		return $this->patch('channels/' . $channelId, $channelData)->then(fn(Response $response) => ChannelTypes::createObject(null, $response->decode()));
	}
	
	public function setChannelPosition(int $guildId, int $channelId, ?int $position, ?int $parentId = -1, bool $syncPermissions = null): Completable {
		return $this->patch('guilds/' . $guildId . '/channels', ['id' => $channelId] + ($position ? ['position' => $position] : []) + ($parentId !== -1 ? ['parent_id' => $parentId, 'lock_permissions' => $syncPermissions] : []));
	}
	
	public function deleteChannel(int $channelId, string $reason = null): Completable {
		return $this->delete('channels/' . $channelId, [], ($reason ? [
			'X-Audit-Log-Reason' => $reason
		] : []));
	}
	
	public function bulkDelete(int $channelId, array $ids, string $reason = null): Completable {
		return $this->post('channels/' . $channelId . '/messages/bulk-delete', ['messages' => $ids], ($reason ? [
			'X-Audit-Log-Reason' => $reason
		] : []));
	}
	
	public function deleteMessage(int $channelId, int $id, string $reason = null): Completable {
		return $this->delete('channels/' . $channelId . '/messages/' . $id, [], ($reason ? [
			'X-Audit-Log-Reason' => $reason
		] : []));
	}
	
	public function getAuditLog(int $guildId): Completable {
		return $this->get('guilds/' . $guildId . '/audit-logs')->then(fn(Response $response) => AuditLog::fromArray($response->decode()));
	}
	
	public function getGuildInvites(int $guildId): Completable {
		return $this->get('guilds/' . $guildId . '/invites')->then(fn(Response $response) => Factory::createInviteArray($response->decode()));
	}
	
	public function getChannelInvites(int $channelId): Completable {
		return $this->get('channels/' . $channelId . '/invites')->then(fn(Response $response) => Factory::createInviteArray($response->decode()));
	}
	
	public function createInvitation(int $channelId, array $data, string $reason = null): Completable {
		return $this->post('channels/' . $channelId . '/invites', $data, ($reason ? [
			'X-Audit-Log-Reason' => $reason
		] : []));
	}
	
	public function getInvite(string $code, bool $withCounts = true, bool $withExpiration = true): Completable {
		return $this->get('invites/' . $code, ['with_counts' => $withCounts, 'with_expiration' => $withExpiration])->then(fn(Response $response) => Invite::fromArray($response->decode()));
	}
	
	public function deleteInvite(string $code, string $reason = null): Completable {
		return $this->delete('invites/' . $code, [], ($reason ? [
			'X-Audit-Log-Reason' => $reason
		] : []));
	}
	
	public function getGuildBans(int $guildId): Completable {
		return $this->get('guilds/' . $guildId . '/bans')->then(fn(Response $response) => Factory::createBanArray($guildId, $response->decode()));
	}
	
	public function getGuildBan(int $guildId, int $userId): Completable {
		return $this->get('guilds/' . $guildId . '/bans/' . $userId)->then(fn(Response $response) => Ban::fromArray(($response->decode() + ['guild_id' => $guildId])));
	}
	
	public function createBan(int $guildId, int $userId, string $reason = null, int $deleteMessagesInDays = null): Completable {
		return $this->put('guilds/' . $guildId . '/bans/' . $userId, [
			'delete_message_days' => $deleteMessagesInDays
		], ($reason ? [
			'X-Audit-Log-Reason' => $reason
		] : []));
	}
	
	public function removeMember(int $guildId, int $userId, string $reason = null): Completable {
		return $this->delete('guilds/' . $guildId . '/members/' . $userId, [], ($reason ? [
			'X-Audit-Log-Reason' => $reason
		] : []));
	}
	
	public function removeBan(int $guildId, int $userId, string $reason = null): Completable {
		return $this->delete('guilds/' . $guildId . '/bans/' . $userId, [], ($reason ? [
			'X-Audit-Log-Reason' => $reason
		] : []));
	}
	
	public function updateMember(int $guildId, int $userId, array $values, string $reason = null): Completable {
		return $this->patch('guilds/' . $guildId . '/members/' . $userId, $values, ($reason ? [
			'X-Audit-Log-Reason' => $reason
		] : []));
	}
	
	public function reactMessage(int $channelId, int $id, PartialEmoji $emoji): Completable {
		return $this->put('channels/' . $channelId . '/messages/' . $id . '/reactions/' . $emoji->encode() . '/@me', []);
	}
	
	/**
	 * @param int|null $userId if the user id is null @me will be used
	 */
	public function deleteReaction(int $channelId, int $id, PartialEmoji $emoji, ?int $userId = null): Completable {
		return $this->delete('channels/' . $channelId . '/messages/' . $id . '/reactions/' . $emoji->encode() . '/' . ($userId ?? '@me'));
	}
	
	public function getReactions(int $channelId, int $id, PartialEmoji $emoji): Completable {
		return $this->get('channels/' . $channelId . '/messages/' . $id . '/reactions/' . $emoji->encode())->then(fn (Response $response) => Factory::createUserArray($response->decode()));
	}
	
	public function deleteAllReactions(int $channelId, int $id): Completable {
		return $this->delete('channels/' . $channelId . '/messages/' . $id . '/reactions');
	}
	
	public function deleteAllEmojiReactions(int $channelId, int $id, PartialEmoji $emoji): Completable {
		return $this->delete('channels/' . $channelId . '/messages/' . $id . '/reactions/' . $emoji->encode());
	}
	
	public function createWebhook(int $channelId, string $name, ?ImageData $avatar, ?string $reason = null): Completable {
		return $this->post('channels/' . $channelId . '/webhooks', [
			'name' => $name,
			'avatar' => $avatar?->encode()
		], ($reason ? [
			'X-Audit-Log-Reason' => $reason
		] : []));
	}
	
	public function getChannelWebhooks(int $channelId): Completable {
		return $this->get('channels/' . $channelId . '/webhooks')->then(fn(Response $response) => Factory::createWebhookArray($response->decode()));
	}
	
	public function getGuildWebhooks(int $guildId): Completable {
		return $this->get('guilds/' . $guildId . '/webhooks')->then(fn(Response $response) => Factory::createWebhookArray($response->decode()));
	}
	
	public function getWebhook(int $webhookId): Completable {
		return $this->get('webhooks/' . $webhookId)->then(fn(Response $response) => Webhook::fromArray($response->decode()));
	}
	
	public function updateWebhook(int $webhookId, array $data, ?string $reason = null): Completable {
		return $this->patch('webhooks/' . $webhookId, $data, ($reason ? [
			'X-Audit-Log-Reason' => $reason
		] : []));
	}
	
	public function deleteWebhook(int $webhookId, ?string $reason = null): Completable {
		return $this->delete('webhooks/' . $webhookId, [], ($reason ? [
			'X-Audit-Log-Reason' => $reason
		] : []));
	}
	
	public function executeWebhook(int $webhookId, string $token, Sendable $sendable): Completable {
		return $this->post('webhooks/' . $webhookId . '/' . $token, $sendable->getBody(), ['Content-Type' => $sendable->getContentType()]);
	}
	
	public function getGlobalSlashCommands(int $applicationId): Completable {
		return $this->get('applications/' . $applicationId . '/commands')->then(fn(Response $response) => Factory::createSlashCommandArray($response->decode()));
	}
	
	public function getGuildSlashCommands(int $applicationId, int $guildId): Completable {
		return $this->get('applications/' . $applicationId . '/guilds/' . $guildId . '/commands')->then(fn(Response $response) => Factory::createSlashCommandArray($response->decode()));
	}
	
	public function deleteGlobalSlashCommand(int $id, int $applicationId): Completable {
		return $this->delete('applications/' . $applicationId . '/commands/' . $id);
	}
	
	public function deleteGuildSlashCommand(int $id, int $applicationId, int $guildId): Completable {
		return $this->delete('applications/' . $applicationId . '/guilds/' . $guildId . '/commands/' . $id);
	}
	
	public function updateGuild(int $id, array $data): Completable {
		return $this->patch('guilds/' . $id, $data);
	}
	
	public function deleteGuild(int $id): Completable {
		return $this->delete('guilds/' . $id);
	}
	
	public function createEmoji(int $guildId, array $data, ?string $reason = null): Completable {
		return $this->post('guilds/' . $guildId . '/emojis', $data, ($reason ? [
			'X-Audit-Log-Reason' => $reason
		] : []));
	}
	
	public function updateEmoji(int $guildId, int $emojiId, array $data, ?string $reason = null): Completable {
		return $this->patch('guilds/' . $guildId . '/emojis/' . $emojiId, $data, ($reason ? [
			'X-Audit-Log-Reason' => $reason
		] : []));
	}
	
	public function deleteEmoji(int $guildId, int $emojiId, ?string $reason = null): Completable {
		return $this->delete('guilds/' . $guildId . '/emojis/' . $emojiId, [], ($reason ? [
			'X-Audit-Log-Reason' => $reason
		] : []));
	}
}