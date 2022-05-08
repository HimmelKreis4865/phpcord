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

interface AttachableLogger {
	
	/**
	 * Logs an information
	 *
	 * @param string $info
	 *
	 * @return void
	 */
	public function info(string $info): void;
	
	/**
	 * Logs a notice which is basically a warning with less importance -> ignorable
	 *
	 * @param string $notice
	 *
	 * @return void
	 */
	public function notice(string $notice): void;
	
	/**
	 * Logs a warning that is no emergency but should be observed
	 *
	 * @param string $warning
	 *
	 * @return void
	 */
	public function warning(string $warning): void;
	
	/**
	 * Logs a critical error that cannot be resolved
	 *
	 * @param string $error
	 *
	 * @return void
	 */
	public function error(string $error): void;
}