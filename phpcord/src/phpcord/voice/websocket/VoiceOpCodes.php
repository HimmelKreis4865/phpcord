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

namespace phpcord\voice\websocket;

use phpcord\utils\enum\EnumTrait;

/**
 * @method static int IDENTIFY()
 * @method static int SELECT()
 * @method static int READY()
 * @method static int HEARTBEAT()
 * @method static int SESSION()
 * @method static int SPEAKING()
 * @method static int HEARTBEAT_ACK()
 * @method static int RESUME()
 * @method static int HELLO()
 * @method static int RESUMED()
 * @method static int CLIENT_DISCONNECT()
 */
final class VoiceOpCodes {
	use EnumTrait;
	
	protected static function make(): void {
		self::register('Identify', 0);
		self::register('Select', 1);
		self::register('Ready', 2);
		self::register('Heartbeat', 3);
		self::register('Session', 4);
		self::register('Speaking', 5);
		self::register('Heartbeat ACK', 6);
		self::register('Resume', 7);
		self::register('Hello', 8);
		self::register('Resumed', 9);
		self::register('Client Disconnect', 13);
	}
}