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

namespace phpcord\async;

use phpcord\autoload\DynamicAutoloader;
use phpcord\cache\DefaultMappingsCache;
use phpcord\logger\Logger;
use function is_scalar;
use function serialize;
use function unserialize;

abstract class Thread extends \Thread {
	use GarbageTrait;
	
	/**
	 * The serialized instanceof @link DynamicAutoloader from cache
	 * @var string $classLoaderSerialized
	 */
	private string $classLoaderSerialized;
	
	private bool $debugEnabled;
	
	/**
	 * Stores the result of the thread (possibly serialized)
	 * @var string|int|float|bool|null
	 */
	private string|int|float|bool|null $result = null;
	
	/** @var bool $resultSerialized */
	private bool $resultSerialized = false;
	
	final public function start(int $options = PTHREADS_INHERIT_ALL) {
		$this->classLoaderSerialized = DefaultMappingsCache::getInstance()->classloader();
		$this->debugEnabled = Logger::isDebugging();
		parent::start($options);
	}
	
	final public function run(): void {
		/** @var DynamicAutoloader $classloader */
		$classloader = unserialize($this->classLoaderSerialized);
		$classloader->autoload();
		Logger::setDebugging($this->debugEnabled);
		$this->onRun();
		$this->setGarbage();
	}
	
	public function setResult(mixed $result): void {
		$this->resultSerialized = !($v = is_scalar($result));
		$this->result = ($v ? $result : serialize($result));
	}
	
	/**
	 * @return mixed
	 */
	public function getResult(): mixed {
		return ($this->resultSerialized ? unserialize($this->result) : $this->result);
	}
	
	abstract protected function onRun(): void;
	
	public function onCompletion(): void {
	
	}
}