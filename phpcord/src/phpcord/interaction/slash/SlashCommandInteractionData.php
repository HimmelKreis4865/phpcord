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

use phpcord\event\interaction\SlashCommandEvent;
use phpcord\interaction\Interaction;
use phpcord\interaction\InteractionData;
use phpcord\utils\Collection;
use phpcord\utils\Utils;
use function array_map;
use function var_dump;

class SlashCommandInteractionData extends InteractionData {
	
	/**
	 * @var Collection $options
	 * @phpstan-var	Collection<OptionResponse>
	 */
	private Collection $options;
	
	/**
	 * @param string $name
	 * @param int $id
	 * @param InteractionDataResolver $resolver
	 * @param OptionResponse[] $options
	 */
	public function __construct(private string $name, private int $id, private InteractionDataResolver $resolver, array $options) {
		$this->options = new Collection($options);
	}
	
	/**
	 * @return InteractionDataResolver
	 */
	public function getResolver(): InteractionDataResolver {
		return $this->resolver;
	}
	
	/**
	 * @return Collection
	 */
	public function getOptions(): Collection {
		return $this->options;
	}
	
	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
	}
	
	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}
	
	public static function fromArray(array $array): ?static {
		if (!Utils::contains($array, 'name', 'id')) return null;
		return new SlashCommandInteractionData($array['name'], $array['id'], ($resolver = InteractionDataResolver::fromArray((($array['resolved'] ?? []) + ['guild_id' => @$array['guild_id']]))), OptionResponse::buildArray($resolver, $array['options'] ?? []));
	}
	
	public static function handle(Interaction $interaction): void {
		(new SlashCommandEvent($interaction, $interaction->getData()->getName(), $interaction->getData()->getOptions()->map(fn(OptionResponse $response) => $response->getValue())))->call();
	}
}