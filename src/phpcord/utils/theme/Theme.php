<?php

namespace phpcord\utils\theme;

abstract class Theme {
	
	final public function __construct() {
	}
	
	/**
	 * Returns the console formatted date time
	 *
	 * @internal
	 *
	 * @param string $hour
	 * @param string $minute
	 * @param string $second
	 * @param string $millisecond
	 *
	 * @return string
	 */
	abstract public function getDateFormat(string $hour, string $minute, string $second, string $millisecond): string;
	
	/**
	 * Returns the console format of an info
	 *
	 * @internal
	 *
	 * @param string $message
	 *
	 * @return string
	 */
	abstract public function getInfoFormat(string $message): string;
	
	/**
	 * Returns the console format of a notice
	 *
	 * @internal
	 *
	 * @param string $message
	 *
	 * @return string
	 */
	abstract public function getNoticeFormat(string $message): string;
	
	/**
	 * Returns the console format of a warning
	 *
	 * @internal
	 *
	 * @param string $message
	 *
	 * @return string
	 */
	abstract public function getWarningFormat(string $message): string;
	
	/**
	 * Returns the console format of a debug
	 *
	 * @internal
	 *
	 * @param string $message
	 *
	 * @return string
	 */
	abstract public function getDebugFormat(string $message): string;
	
	/**
	 * Returns the console format of an error
	 *
	 * @internal
	 *
	 * @param string $message
	 *
	 * @return string
	 */
	abstract public function getErrorFormat(string $message): string;
	
	/**
	 * Returns the console format of an emergency
	 *
	 * @internal
	 *
	 * @param string $message
	 *
	 * @return string
	 */
	abstract public function getEmergencyFormat(string $message): string;
	
	/**
	 * Returns the console format for the end of a log line, anything between this and the next line will have the color format (e.g var_dump)
	 *
	 * @internal
	 *
	 * @return string
	 */
	abstract public function getResetFormat(): string;
}