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

namespace phpcord\application;

use phpcord\image\Icon;
use phpcord\utils\CDN;

class Application {
	
	public function __construct(private int $id, private ?string $name, private ?string $description, private ?Icon $icon, private bool $public, private int $flags) { }
	
	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
	}
	
	/**
	 * @return string|null
	 */
	public function getName(): ?string {
		return $this->name;
	}
	
	/**
	 * @return Icon|null
	 */
	public function getIcon(): ?Icon {
		return $this->icon;
	}
	
	/**
	 * @return string|null
	 */
	public function getDescription(): ?string {
		return $this->description;
	}
	
	/**
	 * @return int
	 */
	public function getFlags(): int {
		return $this->flags;
	}
	
	/**
	 * @return bool
	 */
	public function isPublic(): bool {
		return $this->public;
	}
	
	/**
	 * @param array $array
	 *
	 * @return Application|null
	 */
	public static function fromArray(array $array): ?Application {
		if (!isset($array['id'])) return null;
		return new Application($array['id'], @$array['name'], @$array['description'], ((isset($array['icon_hash']) and $array['icon_hash']) ? new Icon($array['icon_hash'], CDN::APPLICATION_ICON($array['id'], $array['icon_hash'])) : null), $array['bot_public'] ?? false, $array['flags'] ?? 0);
	}
}