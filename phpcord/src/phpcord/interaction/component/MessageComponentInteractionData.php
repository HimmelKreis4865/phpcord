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

use BadMethodCallException;
use JetBrains\PhpStorm\Pure;
use phpcord\event\interaction\ButtonPressEvent;
use phpcord\interaction\Interaction;
use phpcord\interaction\InteractionData;
use phpcord\message\component\ComponentMap;
use phpcord\message\component\ComponentTypes;
use phpcord\message\component\IChildComponent;
use phpcord\message\component\SelectMenu;

class MessageComponentInteractionData extends InteractionData {
	
	/**
	 * @param int $componentType
	 * @param string $customId
	 * @param array|null $values
	 */
	public function __construct(private int $componentType, private string $customId, private ?array $values = null) { }
	
	/**
	 * @return string
	 */
	public function getCustomId(): string {
		return $this->customId;
	}
	
	/**
	 * @return int
	 */
	public function getComponentType(): int {
		return $this->componentType;
	}
	
	/**
	 * @return class-string<IChildComponent>
	 */
	public function getComponentClass(): string {
		return ComponentMap::getInstance()->get($this->getComponentType());
	}
	
	/**
	 * @return array|null
	 */
	public function getValues(): ?array {
		if ($this->getComponentType() !== ComponentTypes::SELECT_MENU()) throw new BadMethodCallException('Cannot call getValues() on interaction of type ' . $this->getComponentClass() . ', expected ' . SelectMenu::class);
		return $this->values;
	}
	
	#[Pure] public static function fromArray(array $array): ?static {
		return new MessageComponentInteractionData($array['component_type'], $array['custom_id'], @$array['values']);
	}
	
	public static function handle(Interaction $interaction): void {
		/** @var class-string<IChildComponent> $class */
		if ($class = $interaction->getData()->getComponentClass()) $class::onInteract($interaction);
	}
}