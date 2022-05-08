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

use ArrayIterator;
use Closure;
use JetBrains\PhpStorm\Pure;
use ReflectionException;
use ReflectionFunction;
use function array_filter;
use function array_key_first;
use function array_key_last;
use function array_keys;
use function array_map;
use function array_values;
use function is_object;
use function mt_rand;
use function spl_object_id;

/**
 * @template T
 * @phpstan-template T
 * @extends ArrayIterator<T>
 */
class Collection extends ArrayIterator {
	
	/**
	 * @param scalar $key
	 *
	 * @return bool
	 */
	public function contains(int|string|float|bool|null $key): bool {
		return $this->offsetExists($key);
	}
	
	/**
	 * @param scalar $key
	 * @param mixed|null $default
	 *
	 * @return T
	 * @phpstan-return T
	 */
	public function get(mixed $key, mixed $default = null) {
		if (!$this->contains($key)) return $default;
		return $this->offsetGet($key) ?? $default;
	}
	
	/**
	 * @param scalar $key
	 * @param mixed $default
	 *
	 * @return T
	 * @phpstan-return T
	 */
	public function reduce(mixed $key, mixed $default = null) {
		if ($this->contains($key)) {
			$r = $this->get($key);
			$this->unset($key);
			return $r;
		}
		return $default;
	}
	
	/**
	 * @param scalar $key
	 * @param T $value
	 * @phpstan-param T $value
	 */
	public function set(mixed $key, mixed $value): void {
		$this->offsetSet($key, $value);
	}
	
	/**
	 * @param T $value
	 * @phpstan-param T $value
	 */
	public function add(mixed $value): void {
		$this->set((is_object($value) ? spl_object_id($value) : mt_rand()), $value);
	}
	
	/**
	 * @param scalar $key
	 *
	 * @return bool returns true on success
	 * false is returned if the expected key does not exist
	 */
	public function unset(mixed $key): bool {
		if (!$this->contains($key)) return false;
		$this->offsetUnset($key);
		return true;
	}
	
	/**
	 * @return T[]
	 * @phpstan-return array<mixed, T>
	 */
	public function asArray(): array {
		return (array) $this;
	}
	
	/**
	 * @param Closure $closure
	 * @phpstan-param Closure(T): mixed
	 *
	 * @return array
	 * @phpstan-return array<scalar, mixed>
	 */
	public function map(Closure $closure): array {
		return array_map($closure, $this->asArray());
	}
	
	/**
	 * @param Closure $closure
	 * @phpstan-param Closure(T): bool
	 *
	 * @return T[]
	 * @phpstan-return array<scalar, T>
	 */
	public function filter(Closure $closure): array {
		return array_filter($this->asArray(), $closure);
	}
	
	/**
	 * @phpstan-return array<int, scalar>
	 */
	#[Pure] public function keys(): array {
		return array_keys($this->asArray());
	}
	
	/**
	 * @phpstan-return array<int, T>
	 */
	#[Pure] public function values(): array {
		return array_values($this->asArray());
	}
	
	/**
	 * @param T[] $array
	 * @phpstan-param array<string, T> $array
	 */
	public function fill(array $array): void {
		$this->clear();
		foreach ($array as $k => $v) $this->set($k, $v);
	}
	
	/**
	 * @param Closure $closure
	 *
	 * @phpstan-param Closure(mixed, T): void
	 */
	public function foreach(Closure $closure): void {
		try {
			if ((new ReflectionFunction($closure))->getNumberOfParameters() === 1) {
				foreach ($this as $v) $closure($v);
				return;
			}
		} catch (ReflectionException) { }
		foreach ($this as $k => $v) $closure($k, $v);
	}
	
	public function clear(): void {
		foreach ($this->getArrayCopy() as $k => $v) $this->offsetUnset($k);
	}
	
	/**
	 * Searches for the first occurrence of a matching @link T
	 *
	 * @param Closure $closure
	 * @phpstan-param Closure(): bool -> return true if the expected T value matches the request, false if it doesn't
	 *
	 * @return T|null
	 */
	public function find(Closure $closure): mixed {
		foreach ($this as $v) if ($closure($v)) return $v;
		return null;
	}
	
	/**
	 * Searches for the all occurrences of a matching @link T
	 *
	 * @param Closure $closure
	 * @phpstan-param Closure(): bool -> return true if the expected T value matches the request, false if it doesn't
	 *
	 * @return array<T>
	 */
	public function findAll(Closure $closure): array {
		return $this->filter(fn($v) => $closure($v));
	}
	
	/**
	 * Returns the first Element of the Collection without removing it
	 *
	 * @return T|null;
	 */
	public function first(): mixed {
		if ($this->empty()) return null;
		return $this->reduce(array_key_first($this->asArray()));
	}
	
	/**
	 * Returns the first Element of the Collection and removes it
	 *
	 * @return T|null
	 */
	public function shift(): mixed {
		if ($this->empty()) return null;
		return $this->reduce(array_key_first($this->asArray()));
	}
	
	/**
	 * Returns the last Element of the Collection without removing it
	 *
	 * @return T|null;
	 */
	public function last(): mixed {
		if ($this->empty()) return null;
		return $this->reduce(array_key_last($this->asArray()));
	}
	
	/**
	 * Returns the last Element of the Collection and removes it
	 *
	 * @return T|null
	 */
	public function pop(): mixed {
		if ($this->empty()) return null;
		return $this->reduce(array_key_last($this->asArray()));
	}
	
	public function empty(): bool {
		return !$this->count();
	}
	
	#[Pure] public function __debugInfo(): array {
		return $this->asArray();
	}
}