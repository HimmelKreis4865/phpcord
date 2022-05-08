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

use phpcord\async\completable\Completable;
use phpcord\channel\types\guild\GuildVoiceChannel;

class VoiceMoveEvent extends VoiceEvent {
	
	/**
	 * @return Completable<GuildVoiceChannel>
	 */
	public function getOldChannel(): Completable {
		return $this->getOldState()->getChannel();
	}
}