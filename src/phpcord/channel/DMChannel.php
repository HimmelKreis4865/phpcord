<?php

namespace phpcord\channel;

use phpcord\user\User;

/**
 * Class DMChannel
 *
 * @warning replying on messages won't work!!!
 *
 * @package phpcord\channel
 */
class DMChannel extends BaseTextChannel {
	/** @var User[] */
	public $recipients = [];
	
	/** @var string|null $last_message_id */
	public $last_message_id;
	
	
	/**
	 * DMChannel constructor.
	 *
	 * @param string $id
	 * @param array $recipients
	 * @param string|null $last_message_id
	 */
	public function __construct(string $id,array $recipients = [], ?string $last_message_id = null) {
		parent::__construct("-", $id, $id, 0, [], $last_message_id);
		$this->last_message_id = $last_message_id;
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