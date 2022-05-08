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

use phpcord\interaction\Interaction;
use phpcord\utils\Collection;

class ModalFormSubmitEvent extends InteractionEvent {
	
	public function __construct(Interaction $interaction, private string $formId, private Collection $components) {
		parent::__construct($interaction);
	}
	
	/**
	 * @return string
	 */
	public function getFormId(): string {
		return $this->formId;
	}
	
	/**
	 * @return Collection<string> [customId => string]
	 */
	public function getComponents(): Collection {
		return $this->components;
	}
}