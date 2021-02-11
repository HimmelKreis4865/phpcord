<?php

namespace phpcord\channel;

interface Sendable {
	/**
	 * Shell return the json data needed to convert the message into a valid message for sending it to discord
	 *
	 * @internal
	 *
	 * @return string
	 */
	public function getFormattedData(): string;
	
	/**
	 * Returns the content type for the message
	 *
	 * @internal
	 *
	 * @return string
	 */
	public function getContentType(): string;
}