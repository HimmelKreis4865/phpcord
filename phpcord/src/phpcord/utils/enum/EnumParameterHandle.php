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

namespace phpcord\utils\enum;

use Closure;

/**
 * @internal
 */
final class EnumParameterHandle {
	
	/**
	 * @param Closure $fn
	 */
	public function __construct(private Closure $fn) { }
	
	/**
	 * @param mixed ...$parameter
	 *
	 * @internal
	 *
	 * @return mixed
	 */
	public function run(mixed ...$parameter): mixed {
		return ($this->fn)(...$parameter);
	}
}