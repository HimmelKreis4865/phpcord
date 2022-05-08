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

namespace phpcord\runtime\network;

use Closure;
use phpcord\logger\CustomLoggerTrait;
use phpcord\runtime\network\packet\MessageBuffer;
use phpcord\runtime\tick\Tickable;
use phpcord\utils\SingletonTrait;
use phpcord\utils\Collection;
use phpcord\utils\helper\MClosure;

/**
 * @internal
 */
final class Network implements Tickable {
	use SingletonTrait;
	use CustomLoggerTrait;
	
	/**
	 * @var Collection<Closure>
	 * @phpstan-var Collection<Closure(MessageSender $sender, MessageBuffer $buffer): void>
	 */
	private Collection $socketReceiveListeners;
	
	/**
	 * The connection to the discord gateway where we receive events from
	 * @var Gateway $gateway
	 */
	private Gateway $gateway;
	
	public function __construct() {
		$this->socketReceiveListeners = new Collection();
	}
	
	public function init(): void {
		$this->gateway = new Gateway();
	}
	
	/**
	 * @param Closure $closure
	 * @phpstan-param Closure(MessageSender $sender, MessageBuffer $buffer): void
	 */
	public function registerListener(Closure $closure): void {
		(new MClosure($closure))->validate(function (MessageSender $sender, MessageBuffer $buffer): void { });
		$this->socketReceiveListeners->add($closure);
	}
	
	public function tick(int $currentTick): void {
		// false means a reconnection is done, so we can skip this atm
		if (!$this->gateway->validate()) return;
		if ($str = $this->gateway->receive())
			$this->socketReceiveListeners->foreach(fn (Closure $closure) => $closure($this->gateway, $str));
	}
	
	/**
	 * @return Gateway
	 */
	public function getGateway(): Gateway {
		return $this->gateway;
	}
}