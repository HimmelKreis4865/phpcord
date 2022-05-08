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

namespace phpcord\interaction;

use phpcord\interaction\component\MessageComponentInteractionData;
use phpcord\interaction\component\ModalInteractionData;
use phpcord\interaction\slash\SlashCommandInteractionData;
use phpcord\utils\Collection;
use phpcord\utils\SingletonTrait;

final class TypeToInteractionDataMap {
	use SingletonTrait;
	
	/**
	 * @var Collection $mappings
	 * @phpstan-var Collection<class-string<InteractionData>>
	 */
	private Collection $mappings;
	
	public function __construct() {
		$this->mappings = new Collection();
		$this->register(InteractionTypes::MESSAGE_COMPONENT(), MessageComponentInteractionData::class);
		$this->register(InteractionTypes::APPLICATION_COMMAND(), SlashCommandInteractionData::class);
		$this->register(InteractionTypes::MODAL_SUBMIT(), ModalInteractionData::class);
	}
	
	/**
	 * @param int $type
	 *
	 * @param string $class
	 * @phpstan-param class-string<InteractionData>
	 *
	 * @return void
	 */
	private function register(int $type, string $class): void {
		$this->mappings->set($type, $class);
	}
	
	/**
	 * @param int $type
	 *
	 * @return class-string<InteractionData>|null
	 */
	public function select(int $type): ?string {
		return $this->mappings->get($type);
	}
}