<?php

namespace phpcord\connection;

use phpcord\Discord;

class ConvertManager {
	/** @var bool $closed */
	public $closed = false;
	/** @var Discord $discord */
	public $discord;

	public $heartbeat_interval = 41500;

	public $last_heartbeat = 0;
}