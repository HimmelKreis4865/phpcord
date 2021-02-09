<?php

namespace phpcord\stream;

interface ReadableInterface extends Expirable {
	/**
	 * Reads from a string, shouldn't be used sync since its blocking
	 *
	 * @internal
	 *
	 * @return false|string
	 */
	public function read();
}


