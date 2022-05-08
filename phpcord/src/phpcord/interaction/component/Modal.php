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

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use JsonSerializable;
use phpcord\message\component\IComponent;

final class Modal implements JsonSerializable {
	
	/**
	 * @param string $title
	 * @param string $customId
	 * @param IComponent[] $components
	 * expected is an array of ActionRows, filled with @see IModalComponent
	 */
	public function __construct(private string $title, private string $customId, private array $components) { }
	
	/**
	 * @return string
	 */
	public function getTitle(): string {
		return $this->title;
	}
	
	/**
	 * @return string
	 */
	public function getCustomId(): string {
		return $this->customId;
	}
	
	/**
	 * @return IComponent[]
	 */
	public function getComponents(): array {
		return $this->components;
	}
	
	/**
	 * @param string $title
	 * @param string $customId
	 * @param IComponent[] $components
	 * expected is an array of ActionRows, filled with @see IModalComponent
	 *
	 * @return Modal
	 */
	#[Pure] public static function new(string $title, string $customId, array $components): Modal {
		return new Modal($title, $customId, $components);
	}
	
	#[ArrayShape(['title' => "string", 'custom_id' => "string", 'components' => "\phpcord\message\component\IComponent[]"])]
	public function jsonSerialize(): array {
		return [
			'title' => $this->title,
			'custom_id' => $this->customId,
			'components' => $this->components
		];
	}
}