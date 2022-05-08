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

namespace phpcord\message\component;

use phpcord\utils\enum\EnumTrait;

/**
 * @method static int ACTION_ROW()
 * @method static int BUTTON()
 * @method static int SELECT_MENU()
 * @method static int TEXT_INPUT()
 */
final class ComponentTypes {
	use EnumTrait;
	
	protected static function make(): void {
		self::register('ACTION_ROW', 1);
		self::register('BUTTON', 2);
		self::register('SELECT_MENU', 3);
		self::register('TEXT_INPUT', 4);
	}
}