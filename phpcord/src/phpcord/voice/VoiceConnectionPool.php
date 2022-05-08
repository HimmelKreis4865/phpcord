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

namespace phpcord\voice;

use phpcord\runtime\tick\Tickable;
use phpcord\utils\Collection;
use phpcord\utils\helper\SPL;
use phpcord\utils\SingletonTrait;
use function var_dump;

final class VoiceConnectionPool implements Tickable {
	use SingletonTrait;
	
	/**
	 * @var Collection
	 * @phpstan-var Collection<VoiceConnection>
	 */
	private Collection $connections;
	
	public function __construct() {
		$this->connections = new Collection();
	}
	
	/**
	 * @param VoiceConnection $connection
	 *
	 * @return void
	 */
	public function registerConnection(VoiceConnection $connection): void {
		$this->connections->add($connection);
	}
	
	/**
	 * @param int|VoiceConnection $connection
	 *
	 * @return VoiceConnection|null
	 */
	public function getConnection(int|VoiceConnection $connection): ?VoiceConnection {
		return $this->connections->get(SPL::id($connection));
	}
	
	public function tick(int $currentTick): void {
		$this->connections->foreach(fn(VoiceConnection $connection) => $connection->tick($currentTick));
	}
}