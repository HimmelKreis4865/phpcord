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

class VoiceStateUpdatePacket extends Packet {
	
	/**
	 * @param int $guildId
	 * @param int $channelId
	 * @param bool $mute
	 * @param bool $deaf
	 */
	public function __construct(private int $guildId, private int $channelId, private bool $mute = false, private bool $deaf = false) { }
	
	#[ArrayShape(['guild_id' => "int", 'channel_id' => "int", 'self_mute' => "bool", 'self_deaf' => "bool"])]
	public function getPayload(): array|string {
		return [
			'guild_id' => $this->guildId,
			'channel_id' => $this->channelId,
			'self_mute' => $this->mute,
			'self_deaf' => $this->deaf
		];
	}
	
	public function getOpCode(): int {
		return Opcodes::VOICE_STATE_UPDATE();
	}
}