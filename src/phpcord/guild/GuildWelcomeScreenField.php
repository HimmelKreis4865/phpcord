<?php

namespace phpcord\guild;

final class GuildWelcomeScreenField {
	/** @var string $channelId */
	protected $channelId;
	
	/** @var string $description */
	protected $description;
	
	/** @var Emoji|null $emoji */
	protected $emoji = null;
	
	public function __construct(string $channelId, string $description = "", ?Emoji $emoji = null) {
		$this->channelId = $channelId;
		$this->description = $description;
		$this->emoji = $emoji;
	}
}