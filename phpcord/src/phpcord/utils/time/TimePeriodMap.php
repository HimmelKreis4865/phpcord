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

namespace phpcord\utils\time;

use Closure;
use phpcord\utils\Collection;
use phpcord\utils\SingletonTrait;
use function floor;
use function is_array;
use function str_replace;

final class TimePeriodMap {
	use SingletonTrait;
	
	private Collection $patterns;
	
	public function __construct() {
		$this->patterns = new Collection();
		$this->loadPatterns();
	}
	
	private function loadPatterns(): void {
		$this->registerPattern('%h', fn(int $seconds) => floor($seconds / 3600) % 24);
		$this->registerPattern('%i', fn(int $seconds) => floor($seconds / 60) % 60);
		$this->registerPattern('%s', fn(int $seconds) => $seconds % 60);
		$this->registerPattern('%d', fn(int $seconds) => floor($seconds / 86400));
	}
	
	public function registerPattern(string|array $patterns, Closure $closure): void {
		foreach ((is_array($patterns) ? $patterns : [$patterns]) as $pattern)
			$this->patterns->set($pattern, $closure);
	}
	
	public function apply(int $seconds, string $format): string {
		$this->patterns->foreach(function (string $key, Closure $mapping) use ($seconds, &$format): void {
			$format = str_replace($key, $mapping($seconds), $format);
		});
		return $format;
	}
}