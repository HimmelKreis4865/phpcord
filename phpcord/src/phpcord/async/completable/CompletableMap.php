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

namespace phpcord\async\completable;

use Closure;
use Exception;
use phpcord\utils\Collection;
use phpcord\utils\helper\SPL;
use phpcord\utils\SingletonTrait;

final class CompletableMap {
	use SingletonTrait;
	
	/**
	 * @var Collection $resolver
	 * @phpstan-var Collection<array<int, Closure(mixed): void>>
	 */
	private Collection $resolves;
	
	/**
	 * @var Collection $rejecter
	 * @phpstan-var Collection<array<int, Closure(Exception): void>>
	 */
	private Collection $rejects;
	
	public function __construct() {
		[$this->resolves, $this->rejects] = [new Collection(), new Collection()];
	}
	
	/**
	 * @param ICompletable|int $completable_or_id
	 * @param Closure $then
	 * @phpstan-param Closure(mixed): void
	 *
	 * @return void
	 */
	public function putResolve(ICompletable|int $completable_or_id, Closure $then): void {
		$this->resolves->set(SPL::id($completable_or_id), [...$this->resolves->get(SPL::id($completable_or_id), []), $then]);
	}
	
	/**
	 * @param ICompletable|int $completable_or_id
	 * @param Closure $catch
	 * @phpstan-param Closure(Exception): void
	 *
	 * @return void
	 */
	public function putReject(ICompletable|int $completable_or_id, Closure $catch): void {
		$this->rejects->set(SPL::id($completable_or_id), [...$this->rejects->get(SPL::id($completable_or_id), []), $catch]);
	}
	
	/**
	 * @param ICompletable|int $completable_or_id
	 *
	 * @return Closure[]
	 * @phpstan-return array<Closure(mixed): void>
	 */
	public function fetchResolves(ICompletable|int $completable_or_id): array {
		$this->rejects->unset(SPL::id($completable_or_id));
		return $this->resolves->reduce(SPL::id($completable_or_id), []);
	}
	
	/**
	 * @param ICompletable|int $completable_or_id
	 *
	 * @return Closure[]
	 * @phpstan-return array<Closure(Exception): void>
	 */
	public function fetchRejects(ICompletable|int $completable_or_id): array {
		$this->resolves->unset(SPL::id($completable_or_id));
		return $this->rejects->reduce(SPL::id($completable_or_id), []);
	}
}