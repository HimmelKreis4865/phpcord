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

namespace phpcord\voice\websocket;

use phpcord\utils\Collection;
use phpcord\utils\SingletonTrait;
use phpcord\voice\VoiceConnection;
use phpcord\voice\websocket\handler\VoiceHelloHandler;
use phpcord\voice\websocket\handler\VoiceOpCodeHandler;

final class VoiceOpCodeHandlerMap {
	use SingletonTrait;
	
	/**
	 * @var Collection $handlers
	 * @phpstan-var	Collection<VoiceOpCodeHandler>
	 */
	private Collection $handlers;
	
	public function __construct() {
		$this->handlers = new Collection();
		
		$this->register(new VoiceHelloHandler(), VoiceOpCodes::HELLO());
	}
	
	public function register(VoiceOpCodeHandler $handler, int ...$opcodes): void {
		foreach ($opcodes as $opcode) $this->handlers->set($opcode, $handler);
	}
	
	public function handle(VoiceConnection $connection, array $payload): void {
		$this->handlers->get($payload['op'])?->handle($connection, $payload);
	}
}