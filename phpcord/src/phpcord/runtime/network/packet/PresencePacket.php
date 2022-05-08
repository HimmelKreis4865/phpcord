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
use JetBrains\PhpStorm\Pure;
use phpcord\runtime\network\opcode\Opcodes;
use phpcord\utils\presence\Status;

class PresencePacket extends Packet {
	
	/**
	 * @param Status $status
	 */
	public function __construct(private Status $status) { }
	
	#[Pure] #[ArrayShape(['since' => "\int|null", 'afk' => "bool", 'status' => "string", 'activities' => "\null[]|\phpcord\utils\presence\Activity[]"])]
	public function getPayload(): array {
		return $this->status->jsonSerialize();
	}
	
	public function getOpCode(): int {
		return Opcodes::PRESENCE_UPDATE();
	}
}