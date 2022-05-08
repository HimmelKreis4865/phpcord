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

namespace phpcord\event\message;

use phpcord\event\Event;
use phpcord\message\Message;

abstract class MessageEvent extends Event {
	
	/**
	 * @param Message $message
	 */
	public function __construct(private Message $message) { }
	
	/**
	 * @return Message
	 */
	public function getMessage(): Message {
		return $this->message;
	}
}