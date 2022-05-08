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

namespace phpcord\runtime\network\opcode;

use phpcord\utils\enum\EnumTrait;

/**
 * @method static int DISPATCH()
 * @method static int HEARTBEAT()
 * @method static int IDENTIFY()
 * @method static int PRESENCE_UPDATE()
 * @method static int VOICE_STATE_UPDATE()
 * @method static int RESUME()
 * @method static int RECONNECT_RECEIVE()
 * @method static int REQUEST_GUILD_MEMBER()
 * @method static int INVALID_SESSION()
 * @method static int HELLO()
 * @method static int HEARTBEAT_ACK()
 */
final class Opcodes {
	use EnumTrait;
	
	protected static function make(): void {
		self::register('DISPATCH', 0);
		self::register('HEARTBEAT', 1);
		self::register('IDENTIFY', 2);
		self::register('PRESENCE_UPDATE', 3);
		self::register('VOICE_STATE_UPDATE', 4);
		self::register('RESUME', 6);
		self::register('RECONNECT_RECEIVE', 7);
		self::register('REQUEST_GUILD_MEMBER', 8);
		self::register('INVALID_SESSION', 9);
		self::register('HELLO', 10);
		self::register('HEARTBEAT_ACK', 11);
	}
}