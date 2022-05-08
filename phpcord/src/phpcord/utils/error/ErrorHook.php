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

namespace phpcord\utils\error;

use Closure;
use Error;
use ErrorException;
use Exception;
use phpcord\Discord;
use phpcord\logger\ColorMap;
use phpcord\logger\Logger;
use phpcord\utils\helper\FileHelper;
use phpcord\utils\SingletonTrait;
use phpcord\utils\Collection;
use phpcord\utils\Utils;
use Throwable;
use function array_map;
use function set_error_handler;
use function set_exception_handler;
use function var_dump;

final class ErrorHook {
	use SingletonTrait;
	
	/**
	 * @var Collection $errorHandlers
	 * @phpstan-var Collection<Closure(Exception): void>
	 */
	private Collection $errorHandlers;
	
	public function __construct() {
		$this->errorHandlers = new Collection();
	}
	
	/**
	 * @throws ErrorException
	 *
	 * @return void
	 */
	public function initiate(): void {
		
		set_error_handler(function (int $severity, string $message, string $file, int $line): void {
			if((error_reporting() & $severity) !== 0) throw new ErrorException($message, 0, $severity, $file, $line);
		});
		
		set_exception_handler(function (Exception|Error $exception): void {
			$logger = Discord::getInstance()?->getLogger() ?? Logger::getLastInstance() ?? new Logger('EmergencyLogger');
			$logger->error(ColorMap::RED() . 'Unhandled ' . ColorMap::ORANGE() . $exception::class . ' "' . FileHelper::printFilter($exception->getMessage()) . '"' . ColorMap::RED() . ' was thrown in ' . ColorMap::ORANGE() . FileHelper::printFilter($exception->getFile()) . ':' . $exception->getLine());
			foreach ($exception->getTrace() as $i => $trace) {
				$logger->error(ColorMap::GREY() . '»' . ColorMap::DEFAULT() . ' Trace ' . ColorMap::ORANGE() . '#' . $i . ColorMap::DEFAULT() . ' ' . FileHelper::printFilter($trace['class'] ?? $trace['file']) . ($trace['type'] ?? '->') . $trace['function'] . ColorMap::ORANGE() . '(' . ColorMap::DEFAULT() . implode(', ', array_map(fn(mixed $value) => Utils::stringifyType($value), $trace['args'] ?? [])) . ColorMap::ORANGE() . ')' . ColorMap::DEFAULT() . '; at ' . FileHelper::printFilter(($trace['file'] ?? $trace['class'])) . ':' . ($trace['line'] ?? -1));
			}
			ErrorHook::getInstance()->getErrorHandlers()->foreach(fn($closure) => $closure($exception));
		});
	}
	
	public function trap(Closure $closure, mixed ...$parameters): ErrorTrapResult {
		try {
			return new ErrorTrapResult(false, $closure(...$parameters));
		} catch (Throwable $throwable) { return new ErrorTrapResult(true, $throwable); }
	}
	
	/**
	 * @return Collection
	 */
	public function getErrorHandlers(): Collection {
		return $this->errorHandlers;
	}
}