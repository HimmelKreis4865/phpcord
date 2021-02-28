<?php

namespace phpcord\event\voice;

use phpcord\event\Event;
use phpcord\guild\Guild;
use phpcord\guild\GuildMember;
use phpcord\guild\VoiceStateData;

class VoiceEvent extends Event {
	
	/** @var string|null $channelId */
	protected $channelId;
	
	/** @var GuildMember|null $member */
	protected $member;
	
	/** @var VoiceStateData $stateData */
	protected $stateData;
	
	/**
	 * VoiceEvent constructor.
	 *
	 * @param string|null $channelId
	 * @param VoiceStateData $data
	 * @param GuildMember|null $member
	 */
	public function __construct(?string $channelId, VoiceStateData $data, ?GuildMember $member) {
		$this->channelId = $channelId;
		$this->member = $member;
		$this->stateData = $data;
	}
	
	/**
	 * Returns the current voice state data of the user involved
	 *
	 * @api
	 *
	 * @return VoiceStateData
	 */
	public function getStateData(): VoiceStateData {
		return $this->stateData;
	}
	
	/**
	 * Returns the userid of the involved user
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getUserId(): string {
		return $this->getStateData()->getUserId();
	}
	
	/**
	 * Returns the channelid the action was made in
	 *
	 * @api
	 *
	 * @return string|null
	 */
	public function getChannelId(): ?string {
		return $this->channelId;
	}
	
	/**
	 * Returns the GuildMember instance of the member involved / null if it wasn't passed
	 *
	 * @api
	 *
	 * @return GuildMember|null
	 */
	public function getMember(): ?GuildMember {
		return $this->member;
	}
	
	/**
	 * Returns the guild id of the action
	 *
	 * @warning null in a dm call
	 *
	 * @api
	 *
	 * @return string|null
	 */
	public function getGuildId(): ?string {
		if ($this->getMember() !== null) return $this->getMember()->getGuildId();
		return null;
	}
	/**
	 * Returns the guild of the action
	 *
	 * @warning null in a dm call
	 *
	 * @api
	 *
	 * @return Guild|null
	 */
	public function getGuild(): ?Guild {
		if ($this->getMember() !== null) return $this->getMember()->getGuild();
		return null;
	}
}