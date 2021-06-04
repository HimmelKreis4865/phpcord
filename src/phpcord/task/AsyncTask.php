<?php

namespace phpcord\task;

use phpcord\Discord;
use phpcord\thread\Thread;
use function is_scalar;
use function is_string;
use function mt_rand;
use function serialize;
use function unserialize;
use const PHP_INT_MAX;

abstract class AsyncTask extends Thread {
	/** @var int $id */
	private $id;
	
	/** @var bool $garbage */
	protected $garbage = false;
	
	/** @var bool $serialized */
	protected $serialized = false;
	
	/** @var scalar|null $result */
	protected $result;
	
	public function __construct() {
		do {
			$this->id = mt_rand(0, PHP_INT_MAX);
		} while (AsyncPool::getInstance()->hasTask($this->id));
	}
	
	final public function onRun() {
		$this->execute();
		$this->setGarbage();
	}
	
	protected function setGarbage(bool $garbage = true) {
		$this->garbage = $garbage;
	}
	
	public function isGarbage(): bool {
		return $this->garbage;
	}
	
	abstract public function execute();
	
	public function setResult($result) {
		if (is_scalar($result)) {
			$this->result = $result;
		} else {
			$this->result = serialize($result);
			$this->serialized = true;
		}
	}
	
	public function onCompletion(Discord $discord): void {
	
	}
	
	/**
	 * @return mixed
	 */
	public function getResult() {
		if ($this->serialized and is_string($this->result)) return unserialize($this->result);
		return $this->result;
	}
	
	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
	}
}