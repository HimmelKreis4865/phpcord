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

namespace phpcord\utils;

use Exception;
use InvalidArgumentException;
use Iterator;
use phpcord\utils\helper\Reflector;
use Traversable;
use function get_resource_type;
use function implode;
use function is_array;
use function is_iterable;
use function is_object;
use function is_resource;
use function iterator_to_array;
use function json_decode;
use function json_encode;
use function strlen;

final class Utils {
	
	public const BOUNDARIES = '___----123456789----___';
	
	public static function stringifyType(mixed $type): string {
		return match (true) {
			is_object($type) => $type::class,
			is_resource($type) => get_resource_type($type),
			is_array($type) => 'array',
			default => (string) $type
		};
	}
	
	public static function exception2string(Exception $exception): string {
		return json_encode(['type' => self::BOUNDARIES, 'class' => $exception::class, 'code' => $exception->getCode(), 'message' => $exception->getMessage(), 'file' => $exception->getFile(), 'line' => $exception->getLine()]);
	}
	
	public static function string2exception(string $string): ?Exception {
		if (!($data = json_decode($string, true)) or ($data['type'] ?? '') !== self::BOUNDARIES) return null;
		$exception = new ($data['class'])($data['message'], $data['code']);
		Reflector::modifyProperty($exception, 'file', $data['file']);
		Reflector::modifyProperty($exception, 'line', $data['line']);
		return $exception;
	}
	
	public static function contains(array $array, string|int|float|bool ...$keys): bool {
		foreach ($keys as $k) {
			if (!array_key_exists($k, $array)) return false;
		}
		return true;
	}
	
	public static function parseHeaders(array $header): array {
		$parsed = [];
		foreach ($header as $name => $v) $parsed[] = $name . ': ' . $v;
		return $parsed;
	}
	
	public static function iterator2array(Traversable $iterator): array {
		$ar = iterator_to_array($iterator);
		foreach ($ar as $k => $v) if ($v instanceof Traversable) $ar[$k] = self::iterator2array($v);
		return $ar;
	}
	
	public static function validateNickname(string $name, int $maxLength = 32): void {
		if (strlen($name) > $maxLength) throw new InvalidArgumentException('A nickname must not exceed the length of ' . $maxLength . ' chars');
		if (Regex::match($name, '/.*(discord|clyde|@|#|:|```).*/i')) throw new InvalidArgumentException('A nickname must not contain the following substrings: discord, clyde, @, #, :, ```');
	}
}