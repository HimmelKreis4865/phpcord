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

use phpcord\utils\NonInstantiableTrait;
use function str_replace;
use const DIRECTORY_SEPARATOR;

final class FileHelper {
	use NonInstantiableTrait;
	
	public static function printFilter(string $path): string {
		return str_replace('\\', '/', str_replace(DATA_PATH, 'phpcord://', str_replace(SRC_PATH, 'core://', $path)));
	}
}