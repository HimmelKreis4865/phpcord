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

namespace phpcord\channel;

use BadMethodCallException;
use Closure;
use phpcord\async\completable\Completable;
use phpcord\utils\Collection;
use function var_dump;

abstract class Channel {
	
	/**
	 * Set to true during fetch progress to not use invalid channels
	 * @var bool $fetching
	 */
	private bool $fetching = false;
	
	/**
	 * @var Collection $fetchHooks
	 * @phpstan-var Collection<Closure(Channel): void>
	 */
	private Collection $fetchHooks;
	
	public function __construct(private int $id) {
		$this->fetchHooks = new Collection();
	}
	
	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
	}
	
	/**
	 * @return Completable<Channel>
	 */
	public function fetch(): Completable {
		$this->fetching = true;
		return $this->internalFetch()->then(function() {
			$this->fetching = false;
			$this->fetchHooks->foreach(fn(Completable $completable) => $completable->complete($this));
			$this->fetchHooks->clear();
		});
	}
	
	/**
	 * @return Completable<Channel>
	 */
	public function newFetchHook(): Completable {
		if (!$this->isFetching()) throw new BadMethodCallException('Cannot set a fetch hook during no fetch progress');
		$c = Completable::sync();
		$this->fetchHooks->add($c);
		return $c;
	}
	
	abstract protected function internalFetch(): Completable;
	
	/**
	 * @return bool
	 */
	public function isFetching(): bool {
		return $this->fetching;
	}
	
	abstract public static function fromArray(array $array): ?self;
}