<?php

namespace phpcord\channel;

use phpcord\user\User;

/**
 * DOES NOT WORK CORRECTLY RIGHT NOW!
 *
 * Class DMChannel
 *
 * @package phpcord\channel
 */
class DMChannel extends BaseTextChannel {
	/** @var User[] */
	public $recipients = [];
	
	/**
	 * DMChannel constructor.
	 *
	 * @param string $guild_id
	 * @param string $id
	 * @param string $name
	 * @param int $position
	 * @param array $recipients
	 * @param string|null $last_message_id
	 */
	public function __construct(string $guild_id, string $id, string $name, int $position = 0, array $recipients = [], ?string $last_message_id = null) {
		parent::__construct($guild_id, $id, $name, $position, [], false, $last_message_id);
		$this->recipients = $recipients;
	}
	
	/**
	 * Returns the channel type, DM in this case
	 *
	 * @api
	 *
	 * @return ChannelType
	 */
	public function getType(): ChannelType {
		return new ChannelType(ChannelType::TYPE_DM);
	}

	/**
	 * Returns a list with all recipients
	 *
	 * @api
	 *
	 * @return User[]
	 */
	public function getRecipients(): array {
		return $this->recipients;
	}
}