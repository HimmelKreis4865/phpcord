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

namespace phpcord\guild\components;

use JetBrains\PhpStorm\Pure;
use phpcord\message\PartialEmoji;

class WelcomeScreenChannel {
	
	/**
	 * @param int $id
	 * @param string|null $description
	 * @param PartialEmoji|null $emoji
	 */
	public function __construct(private int $id, private ?string $description, private ?PartialEmoji $emoji) { }
	
	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
	}
	
	/**
	 * @return string|null
	 */
	public function getDescription(): ?string {
		return $this->description;
	}
	
	/**
	 * @return PartialEmoji|null
	 */
	public function getEmoji(): ?PartialEmoji {
		return $this->emoji;
	}
	
	#[Pure] public static function fromArray(array $array): ?WelcomeScreenChannel {
		if (!isset($array['channel_id'])) return null;
		return new WelcomeScreenChannel($array['channel_id'], @$array['description'], ((@$array['emoji_id'] or @$array['emoji_name']) ? new PartialEmoji(@$array['emoji_name'], @$array['emoji_id']) : null));
	}
}