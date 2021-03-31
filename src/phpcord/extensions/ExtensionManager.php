<?php

namespace phpcord\extensions;

use BadMethodCallException;
use InvalidArgumentException;
use phpcord\Discord;
use phpcord\utils\InstantiableTrait;
use phpcord\utils\MainLogger;
use ReflectionClass;
use function array_diff;
use function array_filter;
use function array_shift;
use function class_exists;
use function file_exists;
use function get_declared_classes;
use function implode;
use function in_array;
use function is_dir;
use function scandir;
use function spl_autoload_register;
use function str_replace;
use function strlen;
use function yaml_parse_file;
use const DIRECTORY_SEPARATOR;

class ExtensionManager {
	use InstantiableTrait;
	/** @var Extension[] $extensions */
	protected $extensions = [];
	
	protected $extensionPaths = [];
	
	public function registerExtensionPath(string $path) {
		if (Discord::getInstance()->isLoggedIn()) throw new BadMethodCallException("Cannot register extensions after startup!");
		if (!is_dir($path)) throw new InvalidArgumentException("Cannot add a not existing extension path!");
		if (!in_array($path, $this->extensionPaths)) $this->extensionPaths[] = $path;
	}
	
	public function loadExtensions() {
		$extCount = 0;
		if (Discord::getInstance()->isLoggedIn()) throw new BadMethodCallException("Cannot load extensions after startup!");
		foreach ($this->extensionPaths as $path) {
			foreach (array_diff(scandir($path), [".", ".."]) as $file) {
				$file = ($path[(strlen($path) - 1)] === DIRECTORY_SEPARATOR ? $path : $path . DIRECTORY_SEPARATOR) . $file;
				if (!is_dir($file)) continue;
				if (!file_exists($file . DIRECTORY_SEPARATOR . "ext.yml")) continue;
				
				$info = ExtensionInfo::fromData(yaml_parse_file($file . DIRECTORY_SEPARATOR . "ext.yml"));
				
				if (isset($this->extensions[$info->getName()])) throw new InvalidArgumentException("Extension " . $info->getName() . " is already registered!");
				
				if (!file_exists(($mainPath = $file . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . str_replace(["/", "\\"], [DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR], $info->getMain()) . ".php")))
					throw new InvalidArgumentException("Main class for extension " . $info->getName() . " not found!");
				
				$classes = get_declared_classes();
				
				require $mainPath;
				$classes = array_diff(get_declared_classes(), $classes);
				$main = array_shift($classes);
				$ref = new ReflectionClass($main);
				if (!$ref->getParentClass() or $ref->getParentClass()->getName() !== Extension::class) throw new InvalidArgumentException("Main file of extension " . $info->getName() . "must be a subclass of " . Extension::class);
				
				spl_autoload_register(function (string $class) use ($file) {
					if (file_exists($file . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . $class . ".php") and !class_exists($class))
						require $file . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . $class . ".php";
				});
				
				$this->extensions[$info->getName()] = new $main($info);
				MainLogger::logNotice("Loading extension " . $info->getName() . " v" . $info->getVersion());
				$extCount++;
			}
		}
		MainLogger::logInfo("Loaded $extCount extensions!");
	}
	
	public function onEnable(): void {
		foreach ($this->extensions as $extension) {
			if (count(($missing = array_filter($extension->getInfo()->getDependencies(), function (string $dependency): bool {
				return !isset($this->extensions[$dependency]);
			}))) > 0) throw new BadMethodCallException("Missing dependencies for extension " . $extension->getInfo()->getName() . ": " . implode(", ", $missing));
			
			MainLogger::logNotice("Enabled extension " . $extension->getInfo()->getName());
			$extension->onEnable();
		}
	}
}