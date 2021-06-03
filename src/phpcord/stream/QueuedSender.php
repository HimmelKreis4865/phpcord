<?php

namespace phpcord\stream;

use phpcord\utils\InstantiableTrait;
use Volatile;

class QueuedSender extends Volatile {
	use InstantiableTrait;
	
	public function queue(string $buffer) {
		$this[] = $buffer;
	}
	
	public function getFullQueue(): array {
		$fullQueue = [];
		
		foreach ($this as $buffer) {
			$fullQueue[] = $buffer;
		}
		return $fullQueue;
	}
	
	public function has(): bool {
		return count($this->getFullQueue());
	}
}