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

namespace phpcord\event\interaction;

use phpcord\event\Event;
use phpcord\interaction\Interaction;

class InteractionEvent extends Event {
	
	/**
	 * @param Interaction $interaction
	 */
	public function __construct(private Interaction $interaction) { }
	
	/**
	 * @return Interaction
	 */
	public function getInteraction(): Interaction {
		return $this->interaction;
	}
}