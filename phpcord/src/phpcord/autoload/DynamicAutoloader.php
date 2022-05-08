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

namespace phpcord\autoload;

use phpcord\cache\DefaultMappingsCache;
use function file_exists;
use function serialize;
use function spl_autoload_register;
use function str_replace;
use const DIRECTORY_SEPARATOR;

final class DynamicAutoloader implements Autoloadable {
	
	/**
	 * @var array $loadElements
	 * @phpstan-var	array<string>
	 */
	private array $loadElements = [];
	
	public function __construct() {
		$this->autoload();
	}
	
	public function load(string $namespace, string $path): void {
		$this->loadElements[$namespace] = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
		DefaultMappingsCache::getInstance()->classloader(serialize($this));
	}
	
	public function autoload(): void {
		spl_autoload_register(function (string $class): void {
			foreach ($this->loadElements as $namespace => $path)
				if (file_exists(($target = str_replace('\\', '/', $path . str_replace($namespace, '', $class) . '.php'))))
					require $target;
		});
	}
}