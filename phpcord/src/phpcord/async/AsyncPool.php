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

namespace phpcord\async;

use phpcord\runtime\tick\Tickable;
use phpcord\utils\Collection;
use phpcord\utils\helper\SPL;
use phpcord\utils\SingletonTrait;

final class AsyncPool implements Tickable {
	use SingletonTrait;
	
	/**
	 * @var Collection $threads
	 * @phpstan-var Collection<Thread> $threads
	 */
	private Collection $threads;
	
	public function __construct() {
		$this->threads = new Collection();
	}
	
	/**
	 * Starts the thread and stores it to the cache, so it can be completed later
	 *
	 * @param Thread $thread
	 *
	 * @return void
	 */
	public function submitThread(Thread $thread): void {
		$this->threads->set(SPL::id($thread), $thread);
		$thread->start();
	}
	
	public function tick(int $currentTick): void {
		/** @var Thread $thread */
		foreach ($this->threads as $id => $thread) {
			if ($thread->isGarbage()) {
				$thread->onCompletion();
				$this->threads->unset($id);
			}
		}
	}
}