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
use ReflectionException;
use ReflectionFunction;

trait CompletableBaseTrait {
	
	/**
	 * If set, the completable will be instantly failured if a @see CompletableBaseTrait::catch() is set
	 * @var Exception|null $preFailure
	 */
	private ?Exception $preFailure = null;
	
	/**
	 * If set, the completable will be instantly completed if a @see CompletableBaseTrait::then() is set
	 * @var mixed|null $preCompleted
	 */
	private mixed $preCompleted = null;
	
	/**
	 * This property exists to decide whether value 'null' is a target value or means not set
	 * @var bool $preCompleteSet
	 */
	private bool $preCompleteSet = false;
	
	/**
	 * Registers a successor that will be executed if everything succeed and the result is valid
	 *
	 * @param Closure $closure
	 *
	 * @return self
	 */
	public function then(Closure $closure): self {
		if ($this->preCompleteSet) {
			$closure($this->preCompleted);
			return $this;
		}
		CompletableMap::getInstance()->putResolve($this, $closure);
		return $this;
	}
	
	/**
	 * Registers a crash handler that will be executed once the script crashed
	 *
	 * @param Closure $closure
	 *
	 * @return self
	 */
	public function catch(Closure $closure): self {
		if ($this->preFailure) {
			$closure($this->preFailure);
			return $this;
		}
		CompletableMap::getInstance()->putReject($this, $closure);
		return $this;
	}
	
	/**
	 * @internal
	 *
	 * @param Exception|mixed $value Exception object will cause the completable to execute catch, otherwise then
	 *
	 * @return void
	 */
	public function complete(mixed $value): void {
		if ($value instanceof Exception) {
			foreach (CompletableMap::getInstance()->fetchRejects($this) as $c) $c($value);
			return;
		}
		foreach (CompletableMap::getInstance()->fetchResolves($this) as $c) {
			$r = $c($value);
			try {
				if ($r !== null or ((new ReflectionFunction($c))->getReturnType()?->allowsNull() ?? false)) $value = $r;
			} catch (ReflectionException) { }
		}
	}
	
	/**
	 * Will fail the completable without actually registering it
	 *
	 * @internal
	 *
	 * @param Exception $exception
	 *
	 * @return void
	 */
	public function __preFail(Exception $exception): void {
		$this->preFailure = $exception;
	}
	
	/**
	 * Will complete the completable without actually registering it
	 *
	 * @internal
	 *
	 * @param mixed $result
	 *
	 * @return void
	 */
	public function __preCompleted(mixed $result): void {
		$this->preCompleted = $result;
		$this->preCompleteSet = true;
	}
}