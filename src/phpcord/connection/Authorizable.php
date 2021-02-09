<?php

namespace phpcord\connection;

interface Authorizable {
	/**
	 * Returns the token needed for a connection to be established
	 *
	 * @internal
	 *
	 * @return string
	 */
	public function getToken(): string;
}


