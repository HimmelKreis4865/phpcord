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

namespace phpcord\runtime\network\opcode;

use phpcord\runtime\network\MessageSender;
use phpcord\runtime\network\Network;
use phpcord\runtime\network\packet\MessageBuffer;

class ReconnectHandler extends OpCodeHandler {
	
	public function getOpCode(): int {
		return Opcodes::RECONNECT_RECEIVE();
	}
	
	public function handle(MessageSender $sender, MessageBuffer $buffer): void {
		Network::getInstance()->getLogger()->warning('Received a reconnect request');
		Network::getInstance()->getGateway()->open();
	}
}