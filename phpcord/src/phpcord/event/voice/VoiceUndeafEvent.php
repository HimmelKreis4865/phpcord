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

namespace phpcord\event\voice;

use JetBrains\PhpStorm\Pure;
use phpcord\voice\VoiceState;

class VoiceUndeafEvent extends VoiceEvent {
	
	/**
	 * @param VoiceState $state
	 * @param VoiceState $oldState
	 * @param bool $self
	 */
	#[Pure] public function __construct(VoiceState $state, VoiceState $oldState, private bool $self) {
		parent::__construct($state, $oldState);
	}
	
	/**
	 * @return bool
	 */
	public function self(): bool {
		return $this->self;
	}
}