<?php

namespace phpcord\guild;

class GuildUpdatedMessage{
	/** @var GuildReceivedEmbed|null $embed */
	public $embed = null;
	/** @var string $id */
	public $id;
	/** @var string $channel_id */
	public $channel_id;
	/** @var string $guild_id */
	public $guild_id;

	/**
	 * GuildUpdatedMessage constructor.
	 *
	 * @param string $guild_id
	 * @param string $id
	 * @param string $channel_id
	 * @param GuildReceivedEmbed|null $embed
	 */
	public function __construct(string $guild_id, string $id, string $channel_id, ?GuildReceivedEmbed $embed = null) {
		$this->guild_id = $guild_id;
		$this->id = $id;
		$this->channel_id = $channel_id;
		$this->embed = $embed;
	}
}