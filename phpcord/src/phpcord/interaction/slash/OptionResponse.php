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

namespace phpcord\interaction\slash;

use phpcord\channel\Channel;
use phpcord\guild\GuildMember;
use phpcord\guild\permissible\Role;
use phpcord\interaction\slash\options\SlashCommandInteractionData;
use phpcord\interaction\slash\options\SlashCommandOptionTypes;
use phpcord\user\User;
use phpcord\utils\Utils;

class OptionResponse {
	
	/**
	 * @param string $name
	 * @param int $type
	 * @param int|float|string|User|Role|Channel|GuildMember $value
	 */
	public function __construct(private string $name, private int $type, private int|float|string|User|Role|Channel|GuildMember $value) { }
	
	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}
	
	/**
	 * @return int
	 */
	public function getType(): int {
		return $this->type;
	}
	
	/**
	 * @return float|int|Channel|GuildMember|Role|User|string
	 */
	public function getValue(): User|float|Channel|Role|int|string|GuildMember {
		return $this->value;
	}
	
	/**
	 * @internal
	 *
	 * @param InteractionDataResolver $data
	 * @param array $array
	 *
	 * @return OptionResponse|null
	 */
	public static function build(InteractionDataResolver $data, array $array): ?OptionResponse {
		if (!Utils::contains($array, 'name', 'value', 'type')) return null;
		$value = $array['value'];
		$value = match ($array['type']) {
			SlashCommandOptionTypes::ROLE() => $data->getRoles()->get((int) $value),
			SlashCommandOptionTypes::CHANNEL() => $data->getChannels()->get((int) $value),
			SlashCommandOptionTypes::USER() => $data->getMembers()->get((int) $value) ?? $data->getUsers()->get((int) $value),
			default => $value
		};
		return new OptionResponse($array['name'], $array['type'], $value);
	}
	
	/**
	 * @param InteractionDataResolver $data
	 * @param array $options
	 *
	 * @return array
	 */
	public static function buildArray(InteractionDataResolver $data, array $options): array {
		$new = [];
		foreach ($options as $array) {
			$o = self::build($data, $array);
			$new[$o->getName()] = $o;
		}
		return $new;
	}
}