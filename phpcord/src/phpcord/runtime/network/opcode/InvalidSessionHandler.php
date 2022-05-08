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
use phpcord\scheduler\Scheduler;

class InvalidSessionHandler extends OpCodeHandler {
	
	public function getOpCode(): int {
		return Opcodes::INVALID_SESSION();
	}
	
	public function handle(MessageSender $sender, MessageBuffer $buffer): void {
		Network::getInstance()->getGateway()->resetSessionDetails();
		Network::getInstance()->getGateway()->close();
		Network::getInstance()->getLogger()->notice('Session invalidated, reconnecting in 2 seconds...');
		Scheduler::getInstance()->delay(fn() => Network::getInstance()->getGateway()->open(), 40);
	}
}