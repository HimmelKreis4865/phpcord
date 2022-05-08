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

namespace phpcord\runtime\network;

final class SessionDetails {
	
	/**
	 * Contains the heartbeat interval in ms, this will most likely be a value between 41000-42000
	 * @var int $heartbeatInterval
	 */
	public int $heartbeatInterval;
	
	/**
	 * Contains the session id from the last hello
	 * Used to reconnect to the socket
	 * @var string $sessionId
	 */
	public string $sessionId;
	
	public function completed(): bool {
		return (isset($this->heartbeatInterval) and isset($this->sessionId));
	}
}