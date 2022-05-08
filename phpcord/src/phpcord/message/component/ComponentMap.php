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

use phpcord\utils\Collection;
use phpcord\utils\SingletonTrait;
use TextInput;

final class ComponentMap {
    use SingletonTrait;
	
	/**
	 * @var Collection $components
     * @phpstan-var Collection<<class-string<IComponent>>
	 */
    private Collection $components;
    
    public function __construct() {
        $this->components = new Collection();
        
        $this->register(ComponentTypes::ACTION_ROW(), ActionRow::class);
        $this->register(ComponentTypes::BUTTON(), Button::class);
		$this->register(ComponentTypes::SELECT_MENU(), SelectMenu::class);
		$this->register(ComponentTypes::TEXT_INPUT(), TextInput::class);
    }
	
	/**
	 * @param int $type
     *
	 * @param string $component
     * @phpstan-param class-string<IComponent>
	 *
	 * @return void
	 */
    public function register(int $type, string $component): void {
        $this->components->set($type, $component);
    }
	
	/**
	 * @param int $type
	 *
	 * @return string|null
	 * @phpstan-return class-string<IComponent>|null
	 */
	public function get(int $type): ?string {
		return $this->components->get($type);
	}
}