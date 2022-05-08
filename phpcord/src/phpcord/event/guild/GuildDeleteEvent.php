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

namespace phpcord\event\guild;

use JetBrains\PhpStorm\Pure;
use phpcord\guild\Guild;

/**
 * This event is called when either a guild gets deleted or the bot gets kicked out of it
 */
class GuildDeleteEvent extends GuildEvent {
	
	#[Pure] public function __construct(?Guild $guild, private int $guildId) {
		parent::__construct($guild);
	}
	
	/**
	 * In case the guild was unavailable previously
	 *
	 * @return int
	 */
	public function getGuildId(): int {
		return $this->guildId;
	}
}