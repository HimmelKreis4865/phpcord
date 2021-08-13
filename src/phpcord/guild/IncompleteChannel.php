<?php

namespace phpcord\guild;

use phpcord\channel\ChannelType;

class IncompleteChannel {
	
	/**
	 * IncompleteChannel constructor.
	 *
	 * @param string $guildId
	 * @param string $id
	 * @param string $name
	 * @param int $type
	 * @param string|null $parent
	 */
	public function __construct(protected string $guildId, protected string $id, protected string $name, protected int $type = ChannelType::TYPE_TEXT, protected ?string $parent = null) {	}
	
	/**
	 * Returns the name of the channel
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
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
	 * Returns the type of the channel @see ChannelType
	 *
	 * @api
	 *
	 * @return int
	 */
	public function getType(): int {
		return $this->type;
	}
	
	/**
	 * @return string
	 */
	public function getGuildId(): string {
		return $this->guildId;
	}
	
	/**
	 * @return string|null
	 */
	public function getParent(): ?string {
		return $this->parent;
	}
}