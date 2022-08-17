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

use phpcord\Discord;
use phpcord\intent\Intents;
use phpcord\runtime\network\MessageSender;
use phpcord\runtime\network\Network;
use phpcord\runtime\network\packet\MessageBuffer;
use phpcord\runtime\network\packet\ResumePacket;
use phpcord\runtime\network\packet\IdentifyPacket;
use function var_dump;

class HelloHandler extends OpCodeHandler {
	
	public function getOpCode(): int {
		return Opcodes::HELLO();
	}
	
	/**
	 * @param MessageSender $sender
	 * @param MessageBuffer $buffer
	 *
	 * @return void
	 */
	public function handle(MessageSender $sender, MessageBuffer $buffer): void {
		$details = Network::getInstance()->getGateway()->getSessionDetails();
		$details->heartbeatInterval = $buffer->asArray()['d']['heartbeat_interval'];
		Network::getInstance()->getGateway()->startHeartbeatTask();
		if ($details->completed()) {
			$sender->sendPacket(new ResumePacket(Discord::getInstance()->getToken(), Network::getInstance()->getGateway()->getSessionDetails()->sessionId, Network::getInstance()->getGateway()->getLastSequence()));
			return;
		}
		$sender->sendPacket(new IdentifyPacket(Discord::getInstance()->getToken(), Discord::getInstance()->getIntents()));
	}
}