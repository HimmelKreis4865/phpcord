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

namespace phpcord\runtime\network\packet;

use JetBrains\PhpStorm\ArrayShape;
use phpcord\runtime\network\opcode\Opcodes;
use phpcord\Version;
use const PHP_OS;

class IdentifyPacket extends Packet {
	
	/**
	 * @param string $token
	 * @param int $intents
	 */
	public function __construct(private string $token, private int $intents) { }
	
	public function getOpCode(): int {
		return Opcodes::IDENTIFY();
	}
	
	#[ArrayShape(['token' => "string", 'intents' => "int", 'properties' => "array"])] public function getPayload(): array {
		return [
			'token' => $this->token,
			'intents' => $this->intents,
			'properties' => [
				'$os' => PHP_OS,
				'$browser' => Version::FULL_NAME,
				'$device' => Version::FULL_NAME
			]
		];
	}
}