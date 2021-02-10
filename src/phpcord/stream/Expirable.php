<?php

namespace phpcord\stream;

interface Expirable {
	/**
	 * Returns whether a Stream is valid or not (= expired)
	 *
	 * @api
	 *
	 * @return bool
	 */
	public function isExpired(): bool;

	/**
	 * Closes stream connection secure
	 *
	 * @internal
	 */
	public function close(): void;
}