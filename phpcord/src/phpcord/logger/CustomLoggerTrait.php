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

namespace phpcord\logger;

use function basename;

trait CustomLoggerTrait {
	
	/** @var Logger|null $logger */
	private ?Logger $logger = null;
	
	/**
	 * Returns the logger for the class with a custom name
	 *
	 * @return Logger
	 */
	public function getLogger(): Logger {
		return ($this->logger ??= new Logger(basename(static::class)));
	}
}