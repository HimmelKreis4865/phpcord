<?php

namespace phpcord\channel;

use OutOfBoundsException;
use OutOfRangeException;
use phpcord\guild\FollowWebhook;
use phpcord\guild\GuildChannel;
use phpcord\guild\GuildInvite;
use phpcord\guild\store\GuildStoredMessage;
use phpcord\guild\Webhook;
use phpcord\http\RestAPIHandler;
use phpcord\utils\DateUtils;
use phpcord\utils\GuildSettingsInitializer;
use phpcord\utils\IntUtils;
use phpcord\utils\MessageInitializer;
use Promise\Promise;
use function array_filter;
use function array_keys;
use function array_map;
use function array_merge;
use function is_string;
use function json_decode;
use function strlen;

class TextChannel extends ExtendedTextChannel {
	/** @var int $rate_limit_per_user */
	public $rate_limit_per_user = 0;
	
	/**
	 * TextChannel constructor.
	 *
	 * @param string $guild_id
	 * @param string $id
	 * @param string $name
	 * @param int $position
	 * @param array $permissions
	 * @param bool $nsfw
	 * @param string|null $last_message_id
	 * @param string|null $topic
	 * @param string|null $parent_id
	 * @param int $rate_limit_per_user
	 */
	public function __construct(string $guild_id, string $id, string $name, int $position = 0, array $permissions = [], bool $nsfw = false, ?string $last_message_id = null, ?string $topic = null, ?string $parent_id = null, int $rate_limit_per_user = 0) {
		parent::__construct($guild_id, $id, $name, $position, $permissions, $nsfw, $last_message_id, $topic, $parent_id);
		$this->rate_limit_per_user = $rate_limit_per_user;
	}
	
	/**
	 * Deletes a sum of messages in a textchannel
	 *
	 * @api
	 *
	 * @param int $messages
	 */
	public function bulkDelete(int $messages = 1): void {
		if ($messages < 1 or $messages > 100) throw new OutOfBoundsException("You can only delete 1-100 messages per call!");

		if ($messages === 1) {
			$this->deleteMessage($this->last_message_id);
			return;
		}

		$this->getMessages($messages)->then(function (array $messages) {
			$ids = array_keys($messages);
			RestAPIHandler::getInstance()->bulkDelete($this->id, $ids);
		});
	}
	
	/**
	 * Deletes a specific message (by id) in a channel
	 *
	 * @api
	 *
	 * @param string $id
	 *
	 * @return Promise
	 */
	public function deleteMessage(string $id): Promise {
		return RestAPIHandler::getInstance()->deleteMessage($id, $this->getId());
	}
	
	/**
	 * Returns an array with all webhooks for this channel
	 *
	 * @api
	 *
	 * @return Webhook[]
	 */
	public function getWebhooks(): array {
		return array_map(function($key) {
			return GuildSettingsInitializer::initWebhook($key);
		}, json_decode(RestAPIHandler::getInstance()->getWebhooksByChannel($this->getId())->getRawData(), true));
	}
	
	/**
	 * Creates a webhook with name and avatar (optional, not tested yet)
	 *
	 * @api
	 *
	 * @param string $name
	 * @param string|null $avatar
	 *
	 * @return Webhook|null
	 */
	public function createWebhook(string $name, ?string $avatar = null): ?Webhook {
		if (strlen($name) > 80 or strlen($name) === 0) return null;
		
		$data = RestAPIHandler::getInstance()->createWebhook($this->getId(), $name, $avatar);
		if ($data->isFailed()) return null;
		if (is_array(($data = json_decode($data->getRawData(), true)))) return GuildSettingsInitializer::initWebhook($data);
		return null;
	}
	
	/**
	 * Follows another news channel
	 *
	 * @param string $targetId
	 *
	 * @return bool
	 */
	public function follow(string $targetId): bool {
		return !RestAPIHandler::getInstance()->followChannel($targetId, $this->getId())->isFailed();
	}
	
	/**
	 * Unfollows another channel if followed
	 *
	 * @param string $targetId
	 * @param bool $isGuildID
	 *
	 * @return void
	 */
	public function unfollow(string $targetId, bool $isGuildID = false): void {
		foreach ($this->getFollows() as $follow) {
			if ($follow->getSourceChannelId() === $targetId or ($isGuildID and $follow->getSourceGuildId() === $targetId)) $follow->delete();
		}
	}
	
	/**
	 * Returns an array with all follows
	 *
	 * @api
	 *
	 * @return FollowWebhook[]
	 */
	public function getFollows(): array {
		return array_filter($this->getWebhooks(), function(Webhook $webhook) {
			return ($webhook instanceof FollowWebhook);
		});
	}
	
	/**
	 * Tries to create an Invitation for the channel
	 *
	 * @api
	 *
	 * @param string|int $duration the duration must be formed like days:hours:minutes:seconds, for only setting 3 days use 3:0:0:0, check out readme for a more detailed description
	 *  0 for infinitive duration
	 * @param int $max_uses 0 = infinitive
	 * @param bool $temporary_membership
	 * @param bool $unique
	 * @param string|null $target_user the id of the target user
	 *
	 * @return GuildInvite|null
	 */
	public function createInvite($duration = 0, int $max_uses = 0, bool $temporary_membership = false, bool $unique = false, ?string $target_user = null): ?GuildInvite {
		if (is_string($duration)) $duration = DateUtils::convertTimeToSeconds($duration);
		if (!IntUtils::isInRange($max_uses, 0, 100)) throw new OutOfRangeException("Max uses must be in range between 0 and 100!");
		$result = RestAPIHandler::getInstance()->createInvite($this->getId(), $duration, $max_uses, $temporary_membership, $unique, $target_user);
		if ($result->isFailed() or strlen($result->getRawData()) === 0) return null;
		return GuildSettingsInitializer::createInvitation(json_decode($result->getRawData(), true));
	}
	
	/**
	 * Fetches all invites for a channel
	 *
	 * @warning invites are fetched from RESTAPI and not stored in cache!
	 *
	 * @api
	 *
	 * @return array
	 */
	public function getInvites(): array {
		$result = RestAPIHandler::getInstance()->getInvitesByChannel($this->getId());
		if ($result->isFailed() or strlen($result->getRawData()) === 0) return [];
		$invites = [];
		foreach (json_decode($result->getRawData(), true) as $invite) {
			$invite = GuildSettingsInitializer::createInvitation($invite);
			$invites[$invite->getCode()] = $invite;
		}
		return $invites;
	}
	
	/**
	 * Changes the parent id (=category) of a channel
	 *
	 * @api
	 *
	 * @param string|null $parent_id
	 */
	public function setParentId(?string $parent_id): void {
		$this->parent_id = $parent_id;
		$this->update();
	}
	
	/**
	 * @see setParentId()
	 *
	 * @param string|null $parent_id
	 */
	public function setCategory(?string $parent_id): void {
		$this->setParentId($parent_id);
	}
	
	/**
	 * @see GuildChannel::getModifyData()
	 *
	 * @internal
	 *
	 * @return array
	 */
	protected function getModifyData(): array {
		return array_merge(parent::getModifyData(), ["parent_id" => $this->parent_id, "rate_limit_per_user" => $this->rate_limit_per_user, "nsfw" => $this->nsfw, "topic" => $this->topic]);
	}
	
	/**
	 * Returns the type of the channel, Text in this case
	 *
	 * @api
	 *
	 * @return ChannelType
	 */
	public function getType(): ChannelType {
		return new ChannelType(ChannelType::TYPE_TEXT);
	}
	
	/**
	 * Returns an array with GuildStoredMessage objects
	 *
	 * @api
	 *
	 * @return GuildStoredMessage[]
	 */
	public function getPins(): array {
		$result = RestAPIHandler::getInstance()->getPins($this->getId());
		if ($result->isFailed() or strlen($result->getRawData()) === 0) return [];
		$messages = [];
		foreach (json_decode($result->getRawData(), true) as $message) {
			$message = MessageInitializer::fromStore($this->getGuildId(), $message);
			$messages[$message->getId()] = $message;
		}
		return $messages;
	}
}