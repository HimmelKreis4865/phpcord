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

namespace phpcord\interaction\component;

use phpcord\interaction\Interaction;
use phpcord\interaction\InteractionData;
use phpcord\event\interaction\ModalFormSubmitEvent;
use phpcord\utils\Collection;

class ModalInteractionData extends InteractionData {
	
	/**
	 * @param string $customId
	 * @param Collection<string> $components
	 */
	public function __construct(private string $customId, private Collection $components) { }
	
	/**
	 * @return string
	 */
	public function getCustomId(): string {
		return $this->customId;
	}
	
	/**
	 * @return Collection<string>
	 */
	public function getComponents(): Collection {
		return $this->components;
	}
	
	public static function handle(Interaction $interaction): void {
		(new ModalFormSubmitEvent($interaction, $interaction->getData()->getCustomId(), $interaction->getData()->getComponents()))->call();
	}
	
	protected static function createComponentCollection(array $components): Collection {
		$c = [];
		foreach ($components as $row) {
			foreach ($row['components'] as $component) {
				$c[$component['custom_id']] = $component['value'];
			}
		}
		return new Collection($c);
	}
	
	public static function fromArray(array $array): ?static {
		return new ModalInteractionData($array['custom_id'], self::createComponentCollection($array['components']));
	}
}