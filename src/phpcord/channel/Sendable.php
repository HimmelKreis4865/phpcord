<?php

namespace phpcord\channel;

interface Sendable {
	/**
	 * Shell return the json data needed to convert the message into a valid message for sending it to discord
	 *
	 * @internal
	 *
	 * @return array
	 */
	public function getJsonData(): array;
}


