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

namespace phpcord\utils\helper;

use Closure;
use phpcord\exception\ClosureSyntaxException;
use ReflectionException;
use ReflectionFunction;
use ReflectionParameter;
use RuntimeException;
use function array_map;
use function implode;

final class MClosure {
	
	/**
	 * @param Closure $closure
	 */
	public function __construct(private Closure $closure) { }
	
	/**
	 * Validates the Closure structure with another
	 *
	 * @api
	 *
	 * @param Closure $validator the validator used
	 */
	public function validate(Closure $validator): void {
		assert((((string) $target = (new MClosure($validator))) === (string) $this), new ClosureSyntaxException($this . ' must be compatible with ' . $target));
	}
	
	public function reflect(): ReflectionFunction {
		try {
			return new ReflectionFunction($this->closure);
		} catch (ReflectionException $e) {
			throw new RuntimeException('Failed to instantiate \\Closure: ' . $e->getMessage());
		}
	}
	
	public function __toString(): string {
		$ref = $this->reflect();
		return 'Closure(' . implode(', ', array_map(function (ReflectionParameter $property): string {
			return $property->getType() . ' $' . $property->getName() . ($property->isOptional() ? ' = ' . $property->getDefaultValue() : '');
		}, $ref->getParameters())) . '): ' . $ref->getReturnType();
	}
}