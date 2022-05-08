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

namespace phpcord\runtime\network\packet;

use function json_encode;

abstract class Packet {
	
	abstract public function getPayload(): mixed;
	
	abstract public function getOpCode(): int;
	
	/**
	 * @param int|null $sequenceNumber
	 *
	 * @return string
	 */
	public function encode(?int $sequenceNumber = null): string {
		return json_encode((($sequenceNumber ? ['s' => $sequenceNumber] : []) + ['op' => $this->getOpCode(), 'd' => $this->getPayload()]));
	}
}