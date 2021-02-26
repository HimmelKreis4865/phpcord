<?php

namespace phpcord\event\user;

use phpcord\client\Activity;
use phpcord\event\Event;
use phpcord\user\User;

/**
 * Class PresenceUpdateEvent
 * A user's presence is their current state on a guild. This event is sent when a user's presence or info, such as name or avatar, is updated.
 *
 * @package phpcord\event\user
 */
class PresenceUpdateEvent extends Event {
	/** @var array[] $activities contains a plain array with all recognized activities (= game status) */
	protected $activities = [];
	
	/** @var string $status */
	protected $status;
	
	protected $userId;
	
	/**
	 * PresenceUpdateEvent constructor.
	 *
	 * @param string $id
	 * @param string $status
	 * @param array $activities
	 */
	public function __construct(string $id, string $status = "", array $activities = []) {
		$this->userId = $id;
		$this->status = $status;
		$this->activities = $activities;
	}
	
	/**
	 * Returns a plain array including all activities
	 *
	 * @api
	 *
	 * @return array[]
	 */
	public function getActivities(): array {
		return $this->activities;
	}
	
	/**
	 * Returns the status string, possibilities:
	 *
	 * @see Activity::STATUS_ONLINE
	 * @see Activity::STATUS_DND
	 * @see Activity::STATUS_IDLE
	 * @see Activity::STATUS_OFFLINE
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getStatus(): string {
		return $this->status;
	}
	
	/**
	 * Returns the ID of the user changed his presence
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getUserId(): string {
		return $this->userId;
	}
}