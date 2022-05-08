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

namespace phpcord\interaction\slash\options;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use phpcord\channel\ChannelTypes;

class ChannelCommandOption extends SlashCommandOption {
	
	/**
	 * @param string $name
	 * @param string $description
	 * @param bool $required
	 *
	 * @param int[] $channelTypes
	 * an array of channel types the options will be restricted to @see ChannelTypes
	 */
	#[Pure] public function __construct(string $name, string $description, bool $required = false, private array $channelTypes = []) {
		parent::__construct($name, $description, $required);
	}
	
	public function getType(): int {
		return SlashCommandOptionTypes::CHANNEL();
	}
	
	#[ArrayShape(['channel_types' => "int[]"])] public function serializeOther(): array {
		return [
			'channel_types' => $this->channelTypes
		];
	}
}