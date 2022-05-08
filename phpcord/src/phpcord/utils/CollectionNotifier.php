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

use Closure;

final class CollectionNotifier extends Collection {
	
	/**
	 * @var Collection $setListener
	 * @phpstan-var Collection<Closure>
	 */
	private Collection $setListener;
	
	/**
	 * @var Collection $removeListener
	 * @phpstan-var Collection<Closure>
	 */
	private Collection $removeListener;
	
	public function __construct(array $array = []) {
		parent::__construct($array);
		$this->setListener = new Collection();
		$this->removeListener = new Collection();
	}
	
	public function registerSetListener(Closure $closure): void {
		$this->setListener->add($closure);
	}
	
	public function registerRemoveListener(Closure $closure): void {
		$this->removeListener->add($closure);
	}
	
	public function set(mixed $key, mixed $value): void {
		parent::set($key, $value);
		$this->setListener->foreach(fn(Closure $closure) => $closure($key, $value));
	}
	
	public function unset(mixed $key): bool {
		$result = parent::unset($key);
		$this->removeListener->foreach(fn(Closure $closure) => $closure($key));
		return $result;
	}
}