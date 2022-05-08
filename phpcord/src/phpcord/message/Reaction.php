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

namespace phpcord\message;

use JetBrains\PhpStorm\Pure;

class Reaction {
	
	/**
	 * @param PartialEmoji $emoji
	 * @param int $count
	 * @param bool $self
	 */
	public function __construct(private PartialEmoji $emoji, private int $count, private bool $self) { }
	
	/**
	 * @return PartialEmoji
	 */
	public function getEmoji(): PartialEmoji {
		return $this->emoji;
	}
	
	/**
	 * @return int
	 */
	public function getCount(): int {
		return $this->count;
	}
	
	/**
	 * Returns whether the current bot reacted with this emoji
	 *
	 * @return bool
	 */
	public function isSelf(): bool {
		return $this->self;
	}
	
	#[Pure] public static function fromArray(array $array): Reaction {
		return new Reaction(PartialEmoji::fromArray($array['emoji']), $array['count'] ?? -1, $array['me'] ?? false);
	}
}