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
use phpcord\runtime\network\packet\MessageBuffer;

abstract class OpCodeHandler {
	
	/**
	 * @internal
	 *
	 * @return int
	 */
	abstract public function getOpCode(): int;
	
	/**
	 * @internal
	 *
	 * @param MessageSender $sender
	 * @param MessageBuffer $buffer
	 *
	 * @return void
	 */
	abstract public function handle(MessageSender $sender, MessageBuffer $buffer): void;
}