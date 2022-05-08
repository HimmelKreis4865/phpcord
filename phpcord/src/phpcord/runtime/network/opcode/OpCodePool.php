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
use phpcord\utils\Collection;
use phpcord\utils\SingletonTrait;
use function var_dump;

final class OpCodePool {
	use SingletonTrait;
	
	/**
	 * @var Collection $opcodes
	 * @phpstan-var Collection<OpCodeHandler>
	 */
	private Collection $opcodes;
	
	public function __construct() {
		$this->opcodes = new Collection();
		$this->register(new HelloHandler());
		$this->register(new DispatchHandler());
		$this->register(new HeartbeatACKHandler());
		$this->register(new InvalidSessionHandler());
		$this->register(new ReconnectHandler());
		$this->register(new HeartbeatHandler());
	}
	
	/**
	 * @param OpCodeHandler $codeHandler
	 *
	 * @return void
	 */
	public function register(OpCodeHandler $codeHandler): void {
		$this->opcodes->set($codeHandler->getOpCode(), $codeHandler);
	}
	
	/**
	 * @internal
	 *
	 * @param MessageSender $sender
	 * @param MessageBuffer $buffer
	 *
	 * @return void
	 */
	public function run(MessageSender $sender, MessageBuffer $buffer): void {
		if (!isset($buffer->asArray()['op'])) {
			Network::getInstance()->getLogger()->error('Payload ' . $buffer . ' does not contain a valid op code');
			return;
		}
		$op = $buffer->asArray()['op'];
		if (!$this->opcodes->contains($op)) {
			Network::getInstance()->getLogger()->notice('Opcode ' . $op . ' is not registered and could not be handled!');
			return;
		}
		$this->opcodes->get($op)->handle($sender, $buffer);
	}
}