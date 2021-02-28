<?php

namespace phpcord\channel;

use phpcord\guild\GuildChannel;
use phpcord\guild\VoiceStateData;

class VoiceChannel extends GuildChannel {
	/** @var int the default bitrate */
	public const DEFAULT_BITRATE = 64000;

	/** @var int $user_limit the limit of the users that are able to join the channel */
	public $user_limit = 0;

	/** @var int can be between 8000 and 128000 */
	public $bitrate = self::DEFAULT_BITRATE;

	/** @var string|null $parent_id */
	public $parent_id = null;
	
	/** @var VoiceStateData[] $users */
	public $users = [];
	
	/**
	 * VoiceChannel constructor.
	 *
	 * @param string $guild_id
	 * @param string $id
	 * @param string $name
	 * @param int $position
	 * @param array $permissions
	 * @param string|null $parent_id
	 * @param int $user_limit
	 * @param int $bitrate
	 */
	public function __construct(string $guild_id, string $id, string $name, int $position = 0, array $permissions = [], ?string $parent_id = null, int $user_limit = 0, int $bitrate = self::DEFAULT_BITRATE) {
		parent::__construct($guild_id, $id, $name, $position, $permissions);
		$this->parent_id = $parent_id;
		$this->user_limit = $user_limit;
		$this->bitrate = $bitrate;
	}
	
	/**
	 * @see GuildChannel::getModifyData()
	 *
	 * @internal
	 *
	 * @return array
	 */
	protected function getModifyData(): array {
		return array_merge(parent::getModifyData(), ["parent_id" => $this->parent_id, "user_limit" => $this->user_limit, "bitrate" => $this->bitrate]);
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
	 * Returns the type of the channel, category in this case
	 *
	 * @api
	 *
	 * @return ChannelType
	 */
	public function getType(): ChannelType {
		return new ChannelType(ChannelType::TYPE_CATEGORY);
	}
}