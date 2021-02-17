<?php

namespace phpcord\event\member;

use phpcord\event\Event;

class MemberTypingStartEvent extends Event {
	
	/** @var string $userId */
	protected $userId;
	
	/** @var string $channelId */
	protected $channelId;
	
	/** @var string $timestamp */
	protected $timestamp;
	
	/**
	 * MemberTypingStartEvent constructor.
	 *
	 * @param string $userId
	 * @param string $timestamp
	 * @param string $channelId
	 */
	public function __construct(string $userId, string $timestamp, string $channelId) {
		$this->userId = $userId;
		$this->channelId = $channelId;
		$this->timestamp = $timestamp;
	}
	
	/**
	 * Returns the ID of the user who started typing
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getUserId(): string {
		return $this->userId;
	}
	
	/**
	 * Returns the timestamp of the start point
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getTimestamp(): string {
		return $this->timestamp;
	}
	
	/**
	 * Returns the ChannelID of the channel the user started typing in
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getChannelId(): string {
		return $this->channelId;
	}
}