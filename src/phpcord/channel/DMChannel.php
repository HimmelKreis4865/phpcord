<?php

namespace phpcord\channel;

use phpcord\guild\GuildChannel;
use phpcord\user\User;

class DMChannel extends BaseTextChannel {
	/** @var User[] */
	public $recipients = [];

	public function __construct(string $guild_id, string $id, string $name, int $position = 0, array $recipients = [], ?string $last_message_id = null) {
		parent::__construct($guild_id, $id, $name, $position, [], false, $last_message_id);
		$this->recipients = $recipients;
	}

	public function getType(): ChannelType {
		return new ChannelType(ChannelType::TYPE_DM);
	}

	/**
	 * @return User[]
	 */
	public function getRecipients(): array {
		return $this->recipients;
	}
}


