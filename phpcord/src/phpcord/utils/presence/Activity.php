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

namespace phpcord\utils\presence;

use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;
use phpcord\utils\enum\EnumParameterHandle;
use phpcord\utils\enum\EnumTrait;
use function array_shift;

/**
 * @method static Activity PLAYING(string $name)
 * @method static Activity STREAMING(string $name)
 * @method static Activity LISTENING(string $name)
 * @method static Activity WATCHING(string $name)
 * @method static Activity CUSTOM(string $name)
 * @method static Activity COMPETING(string $name)
 */
final class Activity implements JsonSerializable {
	use EnumTrait;
	
	/**
	 * @param int $type
	 * @param string $name
	 */
	private function __construct(private int $type, private string $name) { }
	
	protected static function make(): void {
		self::register('PLAYING', new EnumParameterHandle(fn(...$parameters) => new Activity(ActivityType::GAME(), array_shift($parameters))));
		self::register('STREAMING', new EnumParameterHandle(fn(...$parameters) => new Activity(ActivityType::STREAMING(), array_shift($parameters))));
		self::register('LISTENING', new EnumParameterHandle(fn(...$parameters) => new Activity(ActivityType::LISTENING(), array_shift($parameters))));
		self::register('WATCHING', new EnumParameterHandle(fn(...$parameters) => new Activity(ActivityType::WATCHING(), array_shift($parameters))));
		self::register('CUSTOM', new EnumParameterHandle(fn(...$parameters) => new Activity(ActivityType::GAME(), array_shift($parameters))));
		self::register('COMPETING', new EnumParameterHandle(fn(...$parameters) => new Activity(ActivityType::GAME(), array_shift($parameters))));
	}
	
	#[ArrayShape(['type' => "int", 'name' => "string", 'url' => "null"])] public function jsonSerialize(): array {
		return [
			'type' => $this->type,
			'name' => $this->name,
			'url' => null
		];
	}
}