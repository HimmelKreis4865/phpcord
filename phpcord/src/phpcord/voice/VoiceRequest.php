<?php

/*
 *         .__                                       .___
 * ______  |  |__  ______    ____   ____ _______   __| _/
 * \____ \ |  |  \ \____ \ _/ ___\ /  _ \\_  __ \ / __ |
 * |  |_> >|   Y  \|  |_> >\  \___(  <_> )|  | \// /_/ |
 * |   __/ |___|  /|   __/  \___  >\____/ |__|   \____ |
 * |__|         \/ |__|         \/                    \/
 *
 *
 * This library is developed by HimmelKreis4865 © 2022
 *
 * https://github.com/HimmelKreis4865/phpcord
 */

namespace phpcord\voice;

class VoiceRequest {
	
	public string $token;
	
	public string $sessionId;
	
	public string $endpoint;
	
	public bool $hasServerUpdate = false;
	
	/**
	 * @param int $guildId
	 * @param int $userId
	 */
	public function __construct(public int $guildId, public int $userId) { }
	
	public function tryComplete(): void {
		if (!$this->canComplete()) return;
		VoiceRequestPool::getInstance()->removeRequest($this);
		VoiceConnectionPool::getInstance()->registerConnection(new VoiceConnection($this->endpoint, $this->token, $this->sessionId, $this->guildId, $this->userId));
	}
	
	public function canComplete(): bool {
		return (isset($this->token) and isset($this->sessionId) and isset($this->endpoint) and $this->hasServerUpdate);
	}
}