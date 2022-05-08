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

use phpcord\runtime\network\packet\Packet;

interface MessageSender {
	
	/**
	 * Writes a buffer to the sender as response
	 *
	 * @param string $buffer
	 *
	 * @return bool
	 */
	public function write(string $buffer): bool;
	
	/**
	 * Sends a packet to the sender as response
	 *
	 * @param Packet $packet
	 *
	 * @return bool
	 */
	public function sendPacket(Packet $packet): bool;
}