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

namespace phpcord\guild\components;

use phpcord\utils\Collection;
use phpcord\utils\Factory;

class WelcomeScreen {
	
	/**
	 * @var Collection
	 * @phpstan-var Collection<WelcomeScreenChannel>
	 */
	private Collection $welcomeChannels;
	
	public function __construct(private ?string $description, array $welcomeChannels) {
		$this->welcomeChannels = new Collection($welcomeChannels);
	}
	
	/**
	 * @return string|null
	 */
	public function getDescription(): ?string {
		return $this->description;
	}
	
	/**
	 * @return Collection<WelcomeScreenChannel>
	 */
	public function getWelcomeChannels(): Collection {
		return $this->welcomeChannels;
	}
	
	public static function fromArray(array $array): WelcomeScreen {
		 return new WelcomeScreen(@$array['description'], Factory::createWelcomeScreenChannelArray($array['welcome_channels'] ?? []));
	}
}