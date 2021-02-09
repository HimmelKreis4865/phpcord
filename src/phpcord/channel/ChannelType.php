<?php

namespace phpcord\channel;

class ChannelType {

	public const TYPE_TEXT = 0;

	public const TYPE_DM = 1;

	public const TYPE_VOICE = 2;

	public const TYPE_GROUP_DM = 3;

	public const TYPE_CATEGORY = 4;

	public const TYPE_NEWS = 5;

	public const TYPE_STORE = 6;

	private $type;

	public function __construct(int $type) {
		$this->type = $type;
	}

	public function isCategory(): bool {
		return ($this->type === self::TYPE_CATEGORY);
	}

	public function isText(): bool {
		return ($this->type === self::TYPE_TEXT);
	}

	public function isVoice(): bool {
		return ($this->type === self::TYPE_VOICE);
	}
}


