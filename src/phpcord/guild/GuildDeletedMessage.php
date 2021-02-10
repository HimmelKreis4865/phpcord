<?php

namespace phpcord\guild;

class GuildDeletedMessage {
	/** @var string $id */
	public $id;
	
	/** @var string $channel_id */
	public $channel_id;
	
	/** @var string $guild_id */
	public $guild_id;
	
	/**
	 * GuildDeletedMessage constructor.
	 *
	 * @param string $guild_id
	 * @param string $id
	 * @param string $channel_id
	 */
	public function __construct(string $guild_id, string $id, string $channel_id) {
		$this->guild_id = $guild_id;
		$this->id = $id;
		$this->channel_id = $channel_id;
	}
}