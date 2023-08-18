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

use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;
use phpcord\async\completable\Completable;
use phpcord\Discord;
use phpcord\guild\Guild;
use phpcord\guild\permissible\Permission;
use phpcord\http\RestAPI;
use phpcord\utils\Collection;
use function json_encode;

class SlashCommand implements JsonSerializable {
	
	/**
	 * @see SlashCommandTypes
	 * @var int $type
	 */
	private int $type;
	
	private ?int $guildId = null;
	
	private Collection $options;
	
	public function __construct(private string $name, private string $description, array $options, private ?Permission $permission = null, int $type = null) {
		$this->options = new Collection($options);
		$this->type = $type ?? SlashCommandTypes::CHAT_INPUT();
	}
	
	/**
	 * Only apply this if the target command should be a guild slash command
	 *
	 * @param Guild|int $guild_or_id
	 *
	 * @return void
	 */
	public function setTargetGuild(Guild|int $guild_or_id): void {
		$this->guildId = ($guild_or_id instanceof Guild ? $guild_or_id->getId() : $guild_or_id);
	}
	
	/**
	 * Only call this function once or to update the slash command! It's not meant to be registered every bot startup
	 *
	 * @return Completable<PartialSlashCommand>
	 */
	public function register(): Completable {
		return RestAPI::getInstance()->{($this->guildId ? 'registerGuildSlashCommand' : 'registerGlobalSlashCommand')}(Discord::getInstance()->getClient()->getApplication()->getId(), json_encode($this), $this->guildId);
	}
	
	#[ArrayShape(['type' => "int", 'name' => "string", 'description' => "string", 'default_member_permissions' => "int|null", 'options' => "array"])] public function jsonSerialize(): array {
		return [
			'type' => $this->type,
			'name' => $this->name,
			'description' => $this->description,
			'default_member_permissions' => $this->permission?->getPermissionBit(),
			'options' => $this->options->asArray()
		];
	}
}