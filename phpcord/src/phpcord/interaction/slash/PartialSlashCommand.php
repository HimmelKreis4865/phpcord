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

use JetBrains\PhpStorm\Pure;
use phpcord\async\completable\Completable;
use phpcord\http\RestAPI;

class PartialSlashCommand {
	
	/**
	 * @param string $name
	 * @param string $description
	 * @param int $applicationId
	 * @param int $id
	 * @param int|null $guildId
	 */
	private function __construct(private string $name, private string $description, private int $applicationId, private int $id, private ?int $guildId) { }
	
	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}
	
	/**
	 * @return string
	 */
	public function getDescription(): string {
		return $this->description;
	}
	
	/**
	 * @return int
	 */
	public function getApplicationId(): int {
		return $this->applicationId;
	}
	
	/**
	 * @return int|null
	 */
	public function getGuildId(): ?int {
		return $this->guildId;
	}
	
	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
	}
	
	/**
	 * @return Completable
	 */
	public function delete(): Completable {
		return RestAPI::getInstance()->{$this->getGuildId() ? 'deleteGuildSlashCommand' : 'deleteGlobalSlashCommand'}($this->getId(), $this->getApplicationId(), $this->getGuildId());
	}
	
	#[Pure] public static function fromArray(array $array): PartialSlashCommand {
		return new PartialSlashCommand($array['name'], $array['description'], $array['application_id'], $array['id'], @$array['guild_id']);
	}
}