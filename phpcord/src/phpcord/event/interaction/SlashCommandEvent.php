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

use JetBrains\PhpStorm\Pure;
use phpcord\channel\Channel;
use phpcord\guild\GuildMember;
use phpcord\guild\permissible\Role;
use phpcord\interaction\Interaction;
use phpcord\user\User;
use phpcord\utils\Collection;

class SlashCommandEvent extends InteractionEvent {
	
	/**
	 * @var Collection $args
	 * @phpstan-var Collection<User|float|Channel|Role|int|string|GuildMember>
	 */
	private Collection $args;
	
	/**
	 * @param Interaction $interaction
	 * @param string $name
	 * @param User[]|float[]|Channel[]|Role[]|int[]|string[]|GuildMember[] $args
	 */
	public function __construct(Interaction $interaction, private string $name, array $args) {
		parent::__construct($interaction);
		$this->args = new Collection($args);
	}
	
	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}
	
	/**
	 * @return Collection<User|float|Channel|Role|int|string|GuildMember>
	 */
	public function getArgs(): Collection {
		return $this->args;
	}
}