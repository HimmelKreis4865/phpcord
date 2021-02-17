<?php

namespace phpcord\channel;

abstract class Channel {
	/** @var string $id */
	public $id;
	
	/**
	 * Channel constructor.
	 *
	 * @param string $id
	 */
	public function __construct(string $id) {
		$this->id = $id;
	}
	
	/**
	 * Returns the ID of the channel
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getId(): string {
		return $this->id;
	}
	
	/**
	 * Returns the channel type of subclasses
	 *
	 * @internal
	 *
	 * @return ChannelType
	 */
	abstract public function getType(): ChannelType;
}