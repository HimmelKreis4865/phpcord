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

namespace phpcord\channel;

use JsonSerializable;
use phpcord\channel\overwrite\MemberPermissionOverwrite;
use phpcord\channel\overwrite\RolePermissionOverwrite;
use phpcord\utils\enum\EnumParameterHandle;
use phpcord\utils\enum\EnumTrait;
use function array_shift;

/**
 * For constructing $permissionOverwrites, use
 * @link MemberPermissionOverwrite::build()
 * @link RolePermissionOverwrite::build()
 *
 * @method static ChannelBuilder VOICE(string $name, int $bitrate = null, int $userLimit = null, array $permissionOverwrites = [])
 * @method static ChannelBuilder STAGE(string $name, int $bitrate = null, int $userLimit = null, array $permissionOverwrites = [])
 * @method static ChannelBuilder TEXT(string $name, string $topic = null, bool $nsfw = false, int $rateLimit = 0, array $permissionOverwrites = [])
 * @method static ChannelBuilder NEWS(string $name, string $topic = null, bool $nsfw = false, int $rateLimit = 0, array $permissionOverwrites = [])
 */
final class ChannelBuilder implements JsonSerializable {
	use EnumTrait;
	
	/** @var int|null $parentId The category of this channel */
	private ?int $parentId = null;
	
	/** @var int|null $position The position of the channel */
	private ?int $position = null;
	
	/**
	 * @param int $type
	 * @param string $name
	 * @param array $otherData
	 * @param array[] $overwrites
	 */
	private function __construct(private int $type, private string $name, private array $otherData, private array $overwrites = []) { }
	
	protected static function make(): void {
		self::register('VOICE', new EnumParameterHandle(fn(...$parameters) => new ChannelBuilder(ChannelTypes::GUILD_VOICE(), array_shift($parameters), [
			'bitrate' => array_shift($parameters),
			'user_limit' => array_shift($parameters)
		], array_shift($parameters) ?? [])));
		
		self::register('STAGE', new EnumParameterHandle(fn(...$parameters) => new ChannelBuilder(ChannelTypes::GUILD_STAGE_VOICE(), array_shift($parameters), [
			'bitrate' => array_shift($parameters),
			'user_limit' => array_shift($parameters)
		], array_shift($parameters) ?? [])));
		
		self::register('TEXT', new EnumParameterHandle(fn(...$parameters) => new ChannelBuilder(ChannelTypes::GUILD_TEXT(), array_shift($parameters), [
			'topic' => array_shift($parameters),
			'nsfw' => array_shift($parameters) ?? false,
			'rate_limit_per_user' => array_shift($parameters) ?? 0,
		], array_shift($parameters) ?? [])));
		
		self::register('NEWS', new EnumParameterHandle(fn(...$parameters) => new ChannelBuilder(ChannelTypes::GUILD_NEWS(), array_shift($parameters), [
			'topic' => array_shift($parameters),
			'nsfw' => array_shift($parameters) ?? false,
			'rate_limit_per_user' => array_shift($parameters) ?? 0,
		], array_shift($parameters) ?? [])));
	}
	
	/**
	 * @param int $parentId
	 *
	 * @return ChannelBuilder
	 */
	public function setParent(int $parentId): ChannelBuilder {
		$this->parentId = $parentId;
		return $this;
	}
	
	/**
	 * @param int $position
	 *
	 * @return ChannelBuilder
	 */
	public function setPosition(int $position): ChannelBuilder {
		$this->position = $position;
		return $this;
	}
	
	/**
	 * @return int
	 */
	public function getType(): int {
		return $this->type;
	}
	
	public function jsonSerialize(): array {
		return ($this->otherData + [
			'name' => $this->name,
			'type' => $this->type,
			'position' => $this->position,
			'parent_id' => $this->parentId
		]);
	}
}