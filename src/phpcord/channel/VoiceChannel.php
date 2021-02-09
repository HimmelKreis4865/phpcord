<?php

namespace phpcord\channel;

use phpcord\guild\GuildChannel;

class VoiceChannel extends GuildChannel {

	public const DEFAULT_BITRATE = 64000;

	public $user_limit = 0;

	public $bitrate = self::DEFAULT_BITRATE;

	public $parent_id = null;

	public function __construct(string $guild_id, string $id, string $name, int $position = 0, array $permissions = [], ?string $parent_id = null, int $user_limit = 0, int $bitrate = self::DEFAULT_BITRATE) {
		parent::__construct($guild_id, $id, $name, $position, $permissions);
		$this->parent_id = $parent_id;
		$this->user_limit = $user_limit;
		$this->bitrate = $bitrate;
	}

	protected function getModifyData(): array {
		return array_merge(parent::getModifyData(), ["parent_id" => $this->parent_id, "user_limit" => $this->user_limit, "bitrate" => $this->bitrate]);
	}
	
	public function getType(): ChannelType {
		return new ChannelType(ChannelType::TYPE_CATEGORY);
	}
}


