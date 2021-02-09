<?php

namespace phpcord\stream;

interface WriteableInterface extends Expirable {
	/**
	 * Writes a string into a Stream
	 *
	 * @api
	 *
	 * @param string $data
	 * @param bool $final
	 *
	 */
	public function write(string $data, bool $final = true);
}


