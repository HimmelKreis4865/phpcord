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

namespace phpcord\event\client;

use phpcord\event\Cancellable;
use phpcord\event\CancellableTrait;
use phpcord\event\Event;

class ClientPingUpdateEvent extends Event implements Cancellable {
	use CancellableTrait;
	
	/**
	 * @param int $ping the new ping
	 */
	public function __construct(private int $ping) { }
	
	/**
	 * @return int
	 */
	public function getPing(): int {
		return $this->ping;
	}
}