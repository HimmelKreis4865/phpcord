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

namespace phpcord\runtime\tick;

use phpcord\utils\Collection;
use phpcord\utils\SingletonTrait;
use function microtime;

final class Timing {
	use SingletonTrait;
	
	/**
	 * @var Collection $timingRecords
	 * @phpstan-var Collection<float>
	 */
	private Collection $timingRecords;
	
	public function __construct() { $this->timingRecords = new Collection(); }
	
	public function record(string $name): void {
		$this->timingRecords->set($name, $this->time());
	}
	
	public function stop(string $name): float {
		return ($this->time() - $this->timingRecords->reduce($name, $this->time()));
	}
	
	private function time(): float {
		return (microtime(true) * 1000);
	}
}