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

namespace phpcord\event;

use Closure;
use phpcord\exception\EventException;
use phpcord\utils\helper\MClosure;
use phpcord\utils\SingletonTrait;
use ReflectionClass;
use ReflectionMethod;
use function class_exists;
use function is_a;
use function is_subclass_of;
use function var_dump;

final class EventRegistry {
	use SingletonTrait;
	
	/**
	 * @var Closure[][] $eventListeners
	 * @phpstan-var array<class-string<Event>, array<Closure(Event): void>>
	 */
	private array $eventListeners = [];
	
	/**
	 * @param string $eventClass
	 * @param Closure $closure
	 *
	 * @return void
	 */
	public function registerListener(string $eventClass, Closure $closure): void {
		if (!is_subclass_of($eventClass, Event::class))
			throw new EventException('Class ' . $eventClass . ' is no valid subclass of ' . Event::class);
		if (!class_exists((string) ($class = @(($c = new MClosure($closure))->reflect()->getParameters()[0])?->getType())) or ((string) $class !== $eventClass))
			throw new EventException('Parameter #0 of ' . $c . ' must be of type ' . $eventClass . ', ' . $class . ' given');
		$this->eventListeners[$eventClass][] = $closure;
	}
	
	/**
	 * @param object $object
	 *
	 * @return void
	 */
	public function registerListenerObject(object $object): void {
		foreach ((new ReflectionClass($object))->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
			if ($method->getNumberOfParameters() === 1 and !$method->isStatic() and is_subclass_of((string) $method->getParameters()[0]->getType(), Event::class))
				$this->registerListener($method->getParameters()[0]->getType(), $method->getClosure($object));
		}
	}
	
	public function callEvent(Event $event): void {
		foreach ($this->eventListeners[$event::class] ?? [] as $listener) {
			$listener($event);
		}
	}
}