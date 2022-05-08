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

namespace phpcord\async\completable;

use Closure;
use Exception;

interface ICompletable {
	
	/**
	 * Registers a successor that will be executed if everything succeed and the result is valid
	 *
	 * @param Closure $closure
	 *
	 * @return $this
	 */
	public function then(Closure $closure): self;
	
	/**
	 * Registers a crash handler that will be executed once the script crashed
	 *
	 * @param Closure $closure
	 *
	 * @return self
	 */
	public function catch(Closure $closure): self;
	
	/**
	 * @internal
	 *
	 * @param Exception|mixed $value Exception object will cause the completable to execute catch, otherwise then
	 *
	 * @return void
	 */
	public function complete(mixed $value): void;
}