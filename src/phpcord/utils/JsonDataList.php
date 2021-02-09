<?php

namespace phpcord\utils;

interface JsonDataList {

	public const HEARTBEAT = '{ "op": 1, "d": %0% }';

	public const IDENTITY = '{ "op": 2, "d": { "token": "%0%", "intents": %1%, "properties": { "$os": "%2%", "$browser": "DiscordPHP", "$device": "DiscordPHP" } } }';

	//public const IDENTITY = [ "op" => 2, "d" => [ "token" => "%0%", "intents" => "%1", "properties" => [ "\$os" => "linux", "\$browser" => "DiscordPHP", "\$device" => "DiscordPHP" ] ] ];
}


