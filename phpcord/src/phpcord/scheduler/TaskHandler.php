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

namespace phpcord\scheduler;

use Closure;
use phpcord\runtime\tick\Ticker;

class TaskHandler {
	
	/** @var int $lastTick the last time the task was executed / initialized */
	private int $lastTick;
	
	/**
	 * @param Closure $closure
	 * @phpstan-param Closure(): void
	 * @param int $delay_or_period
	 * @param bool $repeating
	 */
	public function __construct(private Closure $closure, private int $delay_or_period, private bool $repeating = false) {
		$this->lastTick = Ticker::getInstance()->getCurrentTick();
	}
	
	public function isExecutingNow(): bool {
		return ((Ticker::getInstance()->getCurrentTick() - $this->lastTick) >= $this->delay_or_period);
	}
	
	public function execute(): void {
		($this->closure)();
		if (!$this->repeating) {
			$this->cancel();
			return;
		}
		$this->lastTick = Ticker::getInstance()->getCurrentTick();
	}
	
	public function cancel(): void {
		Scheduler::getInstance()->cancelTask($this);
	}
}