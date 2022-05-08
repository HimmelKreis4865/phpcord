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

namespace phpcord\autoload;

/**
 * @internal
 */
interface Autoloadable {
	
	/**
	 * Registers a namespace base with its corresponding path to the autoloader
	 *
	 * @param string $namespace
	 * @param string $path
	 *
	 * @return void
	 */
	public function load(string $namespace, string $path): void;
	
}