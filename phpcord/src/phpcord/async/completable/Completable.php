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

use Exception;
use phpcord\async\AsyncPool;
use phpcord\async\Thread;
use phpcord\utils\Utils;
use Volatile;
use function is_callable;
use function is_string;

final class Completable extends Thread implements ICompletable {
	use CompletableBaseTrait;
	
	/** @var callable $workload */
	private $workload;
	
	/** @var Volatile|array $parameters */
	private Volatile|array $parameters;
	
	/**
	 * @param callable|null $workload if specified, the completable will be async
	 * @param mixed ...$parameters
	 */
	private function __construct(?callable $workload = null, mixed ...$parameters) {
		if (is_callable($workload)) {
			$this->workload = $workload;
			$this->parameters = $parameters[0];
			AsyncPool::getInstance()->submitThread($this);
		}
	}
	
	/**
	 * @param callable $workload
	 * @param mixed ...$parameters
	 *
	 * @return Completable
	 */
	public static function async(callable $workload, mixed ...$parameters): Completable {
		return new Completable($workload, $parameters);
	}
	
	/**
	 * @return Completable
	 */
	public static function sync(): Completable {
		return new Completable();
	}
	
	/**
	 * Will create a completable that is already failed to decrease cpu usage
	 *
	 * @param Exception $exception
	 *
	 * @return Completable
	 */
	public static function fail(Exception $exception): Completable {
		$c = self::sync();
		$c->__preFail($exception);
		return $c;
	}
	
	/**
	 * Will create a completable that is already completed to decrease cpu usage
	 *
	 * @param mixed $result
	 *
	 * @return Completable
	 */
	public static function completed(mixed $result): Completable {
		$c = self::sync();
		$c->__preCompleted($result);
		return $c;
	}
	
	protected function onRun(): void {
		try {
			$this->setResult(($this->workload)(...Utils::iterator2array($this->parameters)));
		} catch (Exception $exception) {
			$this->setResult(Utils::exception2string($exception));
		}
	}
	
	public function onCompletion(): void {
		$this->complete((is_string($r = $this->getResult()) ? (Utils::string2exception($r) ?? $r) : $r));
	}
}