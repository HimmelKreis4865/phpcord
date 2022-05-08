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

namespace phpcord\utils\error;

use Closure;

class ErrorTrapResult {
	
	/**
	 * @param bool $failure
	 * @param mixed $result
	 */
	public function __construct(private bool $failure, private mixed $result) { }
	
	/**
	 * @return bool
	 */
	public function isFailed(): bool {
		return $this->failure;
	}
	
	public function success(Closure $closure): ErrorTrapResult {
		if (!$this->failure) $closure($this->result);
		return $this;
	}
	
	public function fail(Closure $closure): ErrorTrapResult {
		if ($this->failure) $closure($this->result);
		return $this;
	}
}