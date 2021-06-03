<?php

namespace phpcord\task;

use phpcord\thread\Thread;
use function mt_rand;
use const PHP_INT_MAX;

abstract class AsyncTask extends Thread {
	/** @var int $id */
	private $id;
	
	public function __construct() {
		do {
			$this->id = mt_rand(0, PHP_INT_MAX);
		} while (AsyncPool::getInstance()->hasTask($this->id));
	}
	
	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
	}
}