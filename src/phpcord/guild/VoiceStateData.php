<?php

namespace phpcord\guild;

class VoiceStateData {
	/** @var bool $mute */
	protected $mute = false;
	
	/** @var bool $deafened */
	protected $deafened = false;
	
	/** @var bool $global_muted */
	protected $global_muted = false;
	
	/** @var bool $global_deafened */
	protected $global_deafened = false;
	
	/** @var bool $streaming */
	protected $streaming = false;
	
	/** @var bool $video */
	protected $video = false;
	
	/** @var string $userId */
	protected $userId;
	
	/**
	 * VoiceStateData constructor.
	 *
	 * @param string $userId
	 * @param bool $mute
	 * @param bool $deafened
	 * @param bool $global_muted
	 * @param bool $global_deafened
	 * @param bool $streaming
	 * @param bool $video
	 */
	public function __construct(string $userId, bool $mute = false, bool $deafened = false, bool $global_muted = false, bool $global_deafened = false, bool $streaming = false, bool $video = false) {
		$this->userId = $userId;
		$this->mute = $mute;
		$this->deafened = $deafened;
		$this->global_muted = $global_muted;
		$this->global_deafened = $global_deafened;
		$this->streaming = $streaming;
		$this->video = $video;
	}
	
	/**
	 * Returns the ID of the user involved
	 *
	 * @api
	 *
	 * @return string
	 */
	public function getUserId(): string {
		return $this->userId;
	}
	
	/**
	 * Returns whether the user is muted or not
	 *
	 * @api
	 *
	 * @return bool
	 */
	public function isMuted(): bool {
		return $this->mute;
	}
	
	/**
	 * Returns whether the user is global muted (by the server) or not
	 *
	 * @api
	 *
	 * @return bool
	 */
	public function isGlobalMuted(): bool {
		return $this->global_muted;
	}
	
	/**
	 * Returns whether the user is deafened or not
	 *
	 * @api
	 *
	 * @return bool
	 */
	public function isDeafened(): bool {
		return $this->deafened;
	}
	
	/**
	 * Returns whether the user is global deafened (by the server) or not
	 *
	 * @api
	 *
	 * @return bool
	 */
	public function isGlobalDeafened(): bool {
		return $this->global_deafened;
	}
	
	/**
	 * Returns whether the user is streaming or not
	 *
	 * @api
	 *
	 * @return bool
	 */
	public function isStreaming(): bool {
		return $this->streaming;
	}
	
	/**
	 * Returns whether the user has video turned on or not
	 *
	 * @api
	 *
	 * @return bool
	 */
	public function hasVideo(): bool {
		return $this->video;
	}
}