<?php

namespace phpcord\event\client;

use phpcord\client\Client;
use phpcord\Discord;
use phpcord\event\Event;

class ClientEvent extends Event {
	/** @var Client $client */
	private $client;
	
	/**
	 * ClientEvent constructor.
	 *
	 * @param Client $client
	 */
	public function __construct(Client $client) {
		$this->client = $client;
	}

	/**
	 * Returns the client instance, can also be get with @link Discord::getClient()
	 *
	 * @api
	 *
	 * @return Client
	 */
	public function getClient(): Client {
		return $this->client;
	}
}