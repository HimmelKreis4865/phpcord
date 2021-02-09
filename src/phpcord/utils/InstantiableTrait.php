<?php

namespace phpcord\utils;

trait InstantiableTrait {
	/** @var null | static $instance */
	protected static $instance = null;

	/**
	 * @return static
	 */
	public static function getInstance(): self {
		if (self::$instance === null) self::$instance = new static();
		return self::$instance;
	}
}


