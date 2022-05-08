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

namespace phpcord\async\net;

use function json_decode;

final class Response {
	
	/**
	 * @param int $code
	 * @param string $payload
	 * @param string[] $headers
	 */
	public function __construct(private int $code, private string $payload, private array $headers = []) { }
	
	/**
	 * @return int
	 */
	public function getCode(): int {
		return $this->code;
	}
	
	/**
	 * @return string[]
	 */
	public function getHeaders(): array {
		return $this->headers;
	}
	
	/**
	 * @return string
	 */
	public function getPayload(): string {
		return $this->payload;
	}
	
	public function decode(): array {
		return json_decode($this->getPayload(), true);
	}
}