<?php

namespace phpcord\event\client;

use phpcord\client\Client;
use phpcord\event\Event;

class ClientEvent extends Event {
	private $client;

	public function __construct(Client $client) {
		$this->client = $client;
	}

	/**
	 * @return Client
	 */
	public function getClient(): Client {
		return $this->client;
	}
}


