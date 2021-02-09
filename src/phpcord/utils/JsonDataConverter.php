<?php

namespace phpcord\utils;

class JsonDataConverter implements JsonDataList {
	public static function getDataString(string $json = self::HEARTBEAT, string ...$replaces) {
		foreach ($replaces as $i => $replace) {
			$json = str_replace("%$i%", $replace, $json);
		}
		return $json;
	}
}


