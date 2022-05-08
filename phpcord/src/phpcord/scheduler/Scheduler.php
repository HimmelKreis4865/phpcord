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

namespace phpcord\scheduler;

use Closure;
use phpcord\runtime\tick\Tickable;
use phpcord\utils\Collection;
use phpcord\utils\helper\SPL;
use phpcord\utils\SingletonTrait;
use function var_dump;

final class Scheduler implements Tickable {
	use SingletonTrait;
	
	/**
	 * @var Collection $workloads
	 * @phpstan-var Collection<TaskHandler>
	 */
	private Collection $workloads;
	
	/**
	 * @var Collection $executionQueue
	 * @phpstan-var Collection<Closure(): void>
	 */
	private Collection $executionQueue;
	
	public function __construct() {
		$this->executionQueue = new Collection();
		$this->workloads = new Collection();
	}
	
	/**
	 * Creates a delayed task for single execution
	 *
	 * @param Closure $closure
	 * @phpstan-param Closure(): void
	 *
	 * @param int $delayInTicks
	 *
	 * @return TaskHandler
	 */
	public function delay(Closure $closure, int $delayInTicks): TaskHandler {
		$handler = new TaskHandler($closure, $delayInTicks);
		$this->workloads->set(SPL::id($handler), $handler);
		return $handler;
	}
	
	/**
	 * Creates a repeating task that will loop the execution until being cancelled @see TaskHandler::cancel()
	 *
	 * @param Closure $closure
	 * @phpstan-param Closure(): void
	 *
	 * @param int $period
	 *
	 * @return TaskHandler
	 */
	public function repeating(Closure $closure, int $period): TaskHandler {
		$handler = new TaskHandler($closure, $period, true);
		$this->workloads->set(SPL::id($handler), $handler);
		return $handler;
	}
	
	/**
	 * @param object|int $object_or_id the object id must be passed to prevent stacking the same closures over and over again
	 * @param Closure $closure
	 * @phpstan-param Closure(): void
	 *
	 * @return void
	 */
	public function executeOnNextTick(object|int $object_or_id, Closure $closure): void {
		$this->executionQueue->set($object_or_id, $closure);
	}
	
	public function tick(int $currentTick): void {
		$this->workloads->foreach(function (TaskHandler $handler): void {
			if ($handler->isExecutingNow()) $handler->execute();
		});
		while ($this->executionQueue->count()) {
			($this->executionQueue->shift())();
		}
	}
	
	public function cancelTask(TaskHandler|int $id): void {
		$this->workloads->unset(SPL::id($id));
	}
}