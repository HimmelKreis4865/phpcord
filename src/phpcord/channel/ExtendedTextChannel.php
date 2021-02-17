<?php

namespace phpcord\channel;

abstract class ExtendedTextChannel extends BaseTextChannel {
	
	/** @var string|null $topic */
	public $topic = null;
	
	/** @var string|null $parent_id */
	public $parent_id = null;
	
	/** @var bool $nsfw */
	public $nsfw;
	
	/**
	 * ExtendedTextChannel constructor.
	 *
	 * @param string $guild_id
	 * @param string $id
	 * @param string $name
	 * @param int $position
	 * @param array $permissions
	 * @param string|null $last_message_id
	 * @param string|null $topic
	 * @param bool $nsfw
	 * @param string|null $parent_id
	 */
	public function __construct(string $guild_id, string $id, string $name, int $position = 0, array $permissions = [], bool $nsfw = false, ?string $last_message_id = null, ?string $topic = null, ?string $parent_id = null) {
		parent::__construct($guild_id, $id, $name, $position, $permissions, $last_message_id);
		$this->nsfw = $nsfw;
		$this->topic = $topic;
		$this->parent_id = $parent_id;
	}
	
	/**
	 * Returns whether this is a nsfw (only adult) channel
	 *
	 * @api
	 *
	 * @return bool
	 */
	public function isNsfw(): bool {
		return $this->nsfw;
	}
	
	/**
	 * Returns the topic of the channel
	 *
	 * @api
	 *
	 * @return string|null
	 */
	public function getTopic(): ?string {
		return $this->topic;
	}
}