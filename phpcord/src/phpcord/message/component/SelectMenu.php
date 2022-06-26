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

namespace phpcord\message\component;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use phpcord\event\interaction\SelectMenuSubmitEvent;
use phpcord\interaction\Interaction;

class SelectMenu implements IChildComponent {
	
	/** @var bool $disabled */
	private bool $disabled = false;
	
	/**
	 * @param string $customId
	 * @param SelectMenuOption[] $options
	 * @param string|null $placeholder This message is shown if no option was selected yet
	 * @param int $minValues
	 * @param int $maxValues
	 */
	public function __construct(private string $customId, private array $options, private ?string $placeholder = null, private int $minValues = 1, private int $maxValues = 1) { }
	
	/**
	 * @param string $customId
	 * @param SelectMenuOption[] $options
	 * @param string|null $placeholder
	 * @param int $minValues
	 * @param int $maxValues
	 *
	 * @return SelectMenu
	 */
	#[Pure] public static function new(string $customId, array $options, ?string $placeholder = null, int $minValues = 1, int $maxValues = 1): SelectMenu {
		return new SelectMenu($customId, $options, $placeholder, $minValues, $maxValues);
	}
	
	/**
	 * @return SelectMenu
	 */
	public function disable(): SelectMenu {
		$this->disabled = true;
		return $this;
	}
	
	public static function onInteract(Interaction $interaction): void {
		(new SelectMenuSubmitEvent($interaction, $interaction->getData()->getCustomId(), $interaction->getData()->getValues()))->call();
	}
	
	#[ArrayShape(['type' => "int", 'custom_id' => "string", 'options' => "array", 'placeholder' => "null|string", 'min_values' => "int", 'max_values' => "int", 'disabled' => "bool"])]
	public function jsonSerialize(): array {
		return [
			'type' => ComponentTypes::SELECT_MENU(),
			'custom_id' => $this->customId,
			'options' => $this->options,
			'placeholder' => $this->placeholder,
			'min_values' => $this->minValues,
			'max_values' => $this->maxValues,
			'disabled' => $this->disabled
		];
	}
}