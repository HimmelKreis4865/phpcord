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

use DateTime;
use RuntimeException;
use const PHP_EOL;

class Logger implements AttachableLogger {
	
	/** @var Logger|null $lastInstance */
	private static ?Logger $lastInstance = null;
	
	/** @var bool $debugMode */
	private static bool $debugMode = false;
	
	/**
	 * @param string|null $name Specifies a name for the logger. Not required
	 */
	public function __construct(private ?string $name = null) {
		self::$lastInstance = $this;
	}
	
	/**
	 * @return Logger|null
	 */
	public static function getLastInstance(): ?Logger {
		return self::$lastInstance;
	}
	
	/**
	 * @param bool $debugMode
	 */
	public static function setDebugging(bool $debugMode): void {
		self::$debugMode = $debugMode;
	}
	
	/**
	 * @internal
	 *
	 * @return bool
	 */
	public static function isDebugging(): bool {
		return self::$debugMode;
	}
	
	/**
	 * Logs an information
	 *
	 * @param string $info
	 *
	 * @return void
	 */
	public function info(string $info): void {
		$this->log('INFO', $info);
	}
	
	/**
	 * Logs a notice which is basically a warning with less importance -> ignorable
	 *
	 * @param string $notice
	 *
	 * @return void
	 */
	public function notice(string $notice): void {
		$this->log(ColorMap::GREY() . 'NOTICE', $notice);
	}
	
	/**
	 * Logs a warning that is no emergency but should be observed
	 *
	 * @param string $warning
	 *
	 * @return void
	 */
	public function warning(string $warning): void {
		$this->log(ColorMap::YELLOW() . 'WARNING', $warning);
	}
	
	/**
	 * Logs a critical error that cannot be resolved
	 *
	 * @param string $error
	 *
	 * @return void
	 */
	public function error(string $error): void {
		$this->log(ColorMap::RED() . 'ERROR', $error);
	}
	
	/**
	 * Logs a debug message that can be enabled / disabled
	 *
	 * @param string $debug
	 *
	 * @return void
	 */
	public function debug(string $debug): void {
		if (self::$debugMode) $this->log(ColorMap::GREEN() . 'DEBUG', $debug);
	}
	
	/**
	 * @internal
	 *
	 * @param string $level
	 * @param string $message
	 *
	 * @return void
	 */
	private function log(string $level, string $message): void {
		echo ColorMap::GREY() . $this->createDateFormat() . ColorMap::DEFAULT() . ' | ' . ($this->name ? $this->name . ColorMap::DEFAULT() . '/' : '') . $level . ColorMap::DEFAULT() . ' | ' . $message . PHP_EOL;
	}
	
	/**
	 * @internal
	 *
	 * @return string
	 */
	private function createDateFormat(): string {
		return (new DateTime('now'))->format('H:i:s.v');
	}
}