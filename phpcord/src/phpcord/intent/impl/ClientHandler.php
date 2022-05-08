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

namespace phpcord\intent\impl;

use phpcord\application\Application;
use phpcord\Client;
use phpcord\Discord;
use phpcord\event\client\ClientReadyEvent;
use phpcord\intent\IntentHandler;
use phpcord\intent\Intents;
use phpcord\runtime\network\Network;
use phpcord\runtime\network\packet\IntentMessageBuffer;
use phpcord\user\User;

class ClientHandler implements IntentHandler {
	
	public function handle(IntentMessageBuffer $buffer): void {
		$d = $buffer->data();
		switch ($buffer->name()) {
			case Intents::READY():
				Network::getInstance()->getGateway()->getSessionDetails()->sessionId = $d['session_id'];
				Discord::getInstance()->__setClient(new Client(User::fromArray($d['user']), Application::fromArray($d['application'])));
				(new ClientReadyEvent())->call();
				break;
		}
	}
}