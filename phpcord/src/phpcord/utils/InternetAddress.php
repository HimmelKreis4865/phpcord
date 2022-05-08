<?php

/*
 *         .__                                       .___
 * ______  |  |__  ______    ____   ____ _______   __| _/
 * \____ \ |  |  \ \____ \ _/ ___\ /  _ \\_  __ \ / __ |
 * |  |_> >|   Y  \|  |_> >\  \___(  <_> )|  | \// /_/ |
 * |   __/ |___|  /|   __/  \___  >\____/ |__|   \____ |
 * |__|         \/ |__|         \/                    \/
 *
 *
 * This library is developed by HimmelKreis4865 © 2022
 *
 * https://github.com/HimmelKreis4865/phpcord
 */

namespace phpcord\utils;

use InvalidArgumentException;
use function explode;
use function substr_count;

class InternetAddress {
	
	/**
	 * @param string $ip a valid IPv4 address
	 * @param int $port [0-65535]
	 */
	public function __construct(protected string $ip, protected int $port) { }
	
	/**
	 * @return string
	 */
	public function getIp(): string {
		return $this->ip;
	}
	
	/**
	 * @return int
	 */
	public function getPort(): int {
		return $this->port;
	}
	
	public function __toString(): string {
		return $this->ip . ':' . $this->port;
	}
	
	public static function fromString(string $address): InternetAddress {
		if (substr_count($address, ':') !== 1) throw new InvalidArgumentException('Address ' . $address . ' is invalid!');
		return new InternetAddress(...explode(':', $address));
	}
}