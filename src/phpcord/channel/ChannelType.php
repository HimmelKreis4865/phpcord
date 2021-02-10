<?php

namespace phpcord\channel;

class ChannelType {
	/** @var int a text channel within a server */
	public const TYPE_TEXT = 0;

	/** @var int a direct message between users */
	public const TYPE_DM = 1;
	
	/** @var int a voice channel within a server*/
	public const TYPE_VOICE = 2;

	/** @var int a direct message between multiple users */
	public const TYPE_GROUP_DM = 3;

	/** @var int a guild category channel that contains up to 50 channels */
	public const TYPE_CATEGORY = 4;

	/** @var int a channel that users can follow and crosspost into their own server */
	public const TYPE_NEWS = 5;

	/** @var int a channel in which game developers can sell their game on Discord */
	public const TYPE_STORE = 6;

	/** @var int $type contains the type passed on construct */
	private $type;
	
	/**
	 * ChannelType constructor.
	 *
	 * @param int $type
	 */
	public function __construct(int $type) {
		$this->type = $type;
	}
	
	/**
	 * Returns whether this channel is a category
	 *
	 * @api
	 *
	 * @return bool
	 */
	public function isCategory(): bool {
		return ($this->type === self::TYPE_CATEGORY);
	}
	/**
	 * Returns whether this channel is a text channel
	 *
	 * @api
	 *
	 * @return bool
	 */
	public function isText(): bool {
		return (in_array($this->type, [self::TYPE_TEXT, self::TYPE_DM, self::TYPE_GROUP_DM, self::TYPE_NEWS, self::TYPE_STORE]));
	}
	
	/**
	 * Returns whether this channel is a voice channel
	 *
	 * @api
	 *
	 * @return bool
	 */
	public function isVoice(): bool {
		return ($this->type === self::TYPE_VOICE);
	}
}