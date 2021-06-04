<?php

namespace phpcord\utils;

use function json_encode;

final class PacketCreator {
	
	public static function buildIdentify(string $token, int $intents = 513): string {
		return json_encode(["op" => 2, "d" => ["token" => $token, "intents" => $intents, "properties" => ["\$os" => "linux", "\$browser" => "phpcord", "\$device" => "phpcord"]]]);
	}
	
	public static function buildHeartbeat(int $seq = null): string {
		return json_encode(["op" => 1, "d" => $seq]);
	}
}