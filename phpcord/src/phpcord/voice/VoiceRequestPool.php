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

use phpcord\utils\Collection;
use phpcord\utils\SingletonTrait;

final class VoiceRequestPool {
	use SingletonTrait;
	
	/**
	 * @var Collection $requests
	 * @phpstan-var Collection<
	 */
	private Collection $requests;
	
	public function __construct() {
		$this->requests = new Collection();
	}
	
	/**
	 * @param VoiceRequest $request
	 *
	 * @return void
	 */
	public function addRequest(VoiceRequest $request): void {
		$this->requests->set($request->guildId, $request);
	}
	
	/**
	 * @param int $guildId
	 *
	 * @return VoiceRequest|null
	 */
	public function getRequest(int $guildId): ?VoiceRequest {
		return $this->requests->get($guildId);
	}
	
	/**
	 * @param int|VoiceRequest $request
	 *
	 * @return void
	 */
	public function removeRequest(int|VoiceRequest $request): void {
		$this->requests->unset(($request instanceof VoiceRequest ? $request->guildId : $request));
	}
	
	/**
	 * @return Collection
	 */
	public function getRequests(): Collection {
		return $this->requests;
	}
}