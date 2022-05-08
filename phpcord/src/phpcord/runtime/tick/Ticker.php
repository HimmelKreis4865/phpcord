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

use Closure;
use phpcord\utils\SingletonTrait;
use phpcord\utils\Collection;
use phpcord\utils\helper\SPL;
use function microtime;
use function usleep;
use function var_dump;

final class Ticker {
	use SingletonTrait;
	
	public const MS_PER_TICK = 25;
	
	public const TICKS_PER_SECOND = (1000 / self::MS_PER_TICK);
	
	/**
	 * @var Collection<Tickable> $registeredInterfaces
	 * @phpstan-var Collection<Tickable>
	 */
	private Collection $registeredInterfaces;
	
	/** @var bool $running */
	protected bool $running = false;
	
	/** @var int $currentTick */
	protected int $currentTick = 0;
	
	/** @var int|null $startTimeMS */
	protected ?int $startTimeMS = null;
	
	private function __construct() {
		$this->registeredInterfaces = new Collection();
	}
	
	/**
	 * @param Tickable $tickable
	 *
	 * @return void
	 */
	public function register(Tickable $tickable): void {
		$this->registeredInterfaces->set(SPL::id($tickable), $tickable);
	}
	
	/**
	 * @param Tickable|int $tickable
	 *
	 * @return bool
	 */
	public function contains(Tickable|int $tickable): bool {
		return $this->registeredInterfaces->contains(($tickable instanceof Tickable ? SPL::id($tickable) : $tickable));
	}
	
	public function getCurrentTick(): int {
		return $this->currentTick;
	}
	
	public function getUptimeMS(): int {
		return (((int) microtime(true) * 1000) - $this->startTimeMS);
	}
	
	public function start(): void {
		$this->startTimeMS = microtime(true) * 1000;
		$this->running = true;
		while ($this->running) {
			$currentTick = $this->currentTick++;
			$this->registeredInterfaces->foreach(function (Tickable $tickable) use ($currentTick) : void {
				$tickable->tick($currentTick);
			});
			usleep(1000 * self::MS_PER_TICK);
		}
		$this->startTimeMS = null;
	}
}