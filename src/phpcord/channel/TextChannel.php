<?php

namespace phpcord\channel;

use phpcord\guild\Webhook;
use phpcord\http\RestAPIHandler;
use phpcord\utils\GuildSettingsInitializer;
use function array_map;
use function array_merge;
use function is_bool;
use function json_decode;
use function strlen;

class TextChannel extends BaseTextChannel {
	/** @var int $rate_limit_per_user */
	public $rate_limit_per_user = 0;


	public function __construct(string $guild_id, string $id, string $name, int $position = 0, array $permissions = [], bool $nsfw = false, ?string $last_message_id = null, ?string $topic = null, ?string $parent_id = null, int $rate_limit_per_user = 0) {
		parent::__construct($guild_id, $id, $name, $position, $permissions, $nsfw, $last_message_id, $topic, $parent_id);
		$this->rate_limit_per_user = $rate_limit_per_user;
	}

	public function bulkDelete(int $messages = 1) {
		if ($messages < 1 or $messages > 100) throw new \OutOfBoundsException("You can only delete 1-100 messages per call!");

		if ($messages === 1) {
			$this->deleteMessage($this->last_message_id);
			return;
		}

		RestAPIHandler::getInstance()->bulkDelete($this->id, $this->getMessageIds($messages));
	}
	
	public function deleteMessage(int $id) {
	
	}
	
	/**
	 * @return Webhook[]
	 */
	public function getWebhooks(): array {
		return array_map(function($key) {
			return GuildSettingsInitializer::initWebhook($key);
		}, json_decode(RestAPIHandler::getInstance()->getWebhooksByChannel($this->getId())->getRawData(), true));
	}
	
	public function createWebhook(string $name, ?string $avatar = null): ?Webhook {
		if (strlen($name) > 80 or strlen($name) === 0) return null;
		
		$data = RestAPIHandler::getInstance()->createWebhook($this->getId(), $name, $avatar);
		if (is_bool($data)) return null;
		if (is_array(($data = json_decode($data->getRawData(), true)))) return GuildSettingsInitializer::initWebhook($data);
		return null;
	}
	
	protected function getModifyData(): array {
		return array_merge(parent::getModifyData(), ["parent_id" => $this->parent_id, "rate_limit_per_user" => $this->rate_limit_per_user, "nsfw" => $this->nsfw, "topic" => $this->topic]);
	}
	
	public function getType(): ChannelType {
		return new ChannelType(ChannelType::TYPE_TEXT);
	}
}


