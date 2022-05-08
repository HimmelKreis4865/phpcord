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
use phpcord\interaction\Interaction;

class SelectMenuSubmitEvent extends InteractionEvent {
	
	/**
	 * @param Interaction $interaction
	 * @param string $customId
	 * @param array $selectedValues
	 */
	#[Pure] public function __construct(Interaction $interaction, private string $customId, private array $selectedValues) {
		parent::__construct($interaction);
	}
	
	/**
	 * @return string
	 */
	public function getCustomId(): string {
		return $this->customId;
	}
	
	/**
	 * Returns the selected values, will at least contain one element
	 *
	 * @return string[]
	 */
	public function getSelectedValues(): array {
		return $this->selectedValues;
	}
}