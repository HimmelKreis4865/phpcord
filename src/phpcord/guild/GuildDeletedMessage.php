<?php

namespace phpcord\guild;

class GuildDeletedMessage {

	public $id;

	public $channel_id;

	public $guild_id;

	public function __construct(string $guild_id, string $id, string $channel_id) {
		$this->guild_id = $guild_id;
		$this->id = $id;
		$this->channel_id = $channel_id;
	}
}


