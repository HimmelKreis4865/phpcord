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
use phpcord\async\completable\Completable;
use phpcord\channel\types\guild\GuildVoiceChannel;
use phpcord\event\Event;
use phpcord\guild\Guild;
use phpcord\guild\GuildMember;
use phpcord\voice\VoiceState;

abstract class VoiceEvent extends Event {
	
	/**
	 * @param VoiceState $state
	 * @param VoiceState|null $oldState
	 */
	public function __construct(private VoiceState $state, private ?VoiceState $oldState = null) { }
	
	/**
	 * @return VoiceState
	 */
	public function getState(): VoiceState {
		return $this->state;
	}
	
	/**
	 * @return VoiceState|null
	 */
	public function getOldState(): ?VoiceState {
		return $this->oldState;
	}
	
	#[Pure] public function getMember(): GuildMember {
		return $this->state->getMember();
	}
	
	public function getGuild(): Guild {
		return $this->state->getGuild();
	}
	
	/**
	 * @return Completable<GuildVoiceChannel>
	 */
	public function getChannel(): Completable {
		return $this->getState()->getChannel();
	}
}