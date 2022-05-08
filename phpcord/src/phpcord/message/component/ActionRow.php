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
use phpcord\utils\Collection;

class ActionRow implements IComponent {
	
	/**
	 * @var Collection $components
	 * @phpstan-var Collection<IChildComponent>
	 */
	private Collection $components;
	
	/**
	 * @param IChildComponent[] $components
	 */
	public function __construct(array $components = []) {
		$this->components = new Collection($components);
		$this->validate();
	}
	
	/**
	 * @param IChildComponent $component
	 *
	 * @return void
	 */
	public function add(IChildComponent $component): void {
		$this->components->add($component);
		$this->validate();
	}
	
	public static function new(IChildComponent ...$components): ActionRow {
		return new ActionRow($components);
	}
	
	private function validate(): void {
		// todo
	}
	
	#[ArrayShape(['type' => "int", 'components' => "phpcord\message\component\Button[]"])]
	public function jsonSerialize(): array {
		return [
			'type' => ComponentTypes::ACTION_ROW(),
			'components' => array_values($this->components->asArray())
		];
	}
}