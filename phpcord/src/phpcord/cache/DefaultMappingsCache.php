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

namespace phpcord\cache;

use phpcord\utils\Collection;
use phpcord\utils\SingletonTrait;
use RuntimeException;
use function array_shift;

/**
 * @internal
 *
 * @method string classloader(string $default = null)
 */
final class DefaultMappingsCache {
	use SingletonTrait;
	
	/**
	 * @var Collection $mappings
	 * @phpstan-var Collection<mixed>
	 */
	private Collection $mappings;
	
	public function __construct() {
		$this->mappings = new Collection();
	}
	
	public function __call(string $name, array $arguments) {
		if (!$this->mappings->contains($name) and !count($arguments))
			throw new RuntimeException('Failed to request cache entry ' . $name . ' with no fallback value set.');
		$v = $this->mappings->get($name);
		if ($v === null) $this->mappings->set($name, ($v = array_shift($arguments)));
		return $v;
	}
}