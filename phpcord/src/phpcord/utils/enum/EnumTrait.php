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

namespace phpcord\utils\enum;

use phpcord\exception\EnumException;
use phpcord\utils\Collection;
use function str_replace;
use function strtoupper;

/**
 * @template T
 * @phpstan-template T
 */
trait EnumTrait {
	
	/**
	 * @var Collection $members
	 * @phpstan-var Collection<T>
	 */
	private static Collection $members;
	
	abstract protected static function make(): void;
	
	/**
	 * @param string $name
	 * @param array $arguments
	 *
	 * @return T
	 * @phpstan-return T
	 */
	public static function __callStatic(string $name, array $arguments) {
		self::init();
		if (!self::$members->contains($name = self::parseName($name))) throw new EnumException('Enum ' . static::class . '::' . $name . '() is not existent.');
		$member = self::$members->get($name);
		return ($member instanceof EnumParameterHandle ? $member->run(...$arguments) : $member);
	}
	
	/**
	 * @param string $name
	 * @param T $value
	 * @phpstan-param T
	 *
	 * @return void
	 */
	protected static function register(string $name, mixed $value): void {
		self::$members->set(self::parseName($name), $value);
	}
	
	/**
	 * @param string $name
	 *
	 * @return string
	 */
	private static function parseName(string $name): string {
		return strtoupper(str_replace(' ', '_', $name));
	}
	
	private static function init(): void {
		if (isset(self::$members)) return;
		
		self::$members = new Collection();
		static::make();
	}
}