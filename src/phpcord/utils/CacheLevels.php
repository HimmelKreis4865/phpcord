<?php

namespace phpcord\utils;

use phpcord\Discord;

final class CacheLevels {
	public const CACHE_ALL = 0;
	
	public const CACHE_NO_MEMBERS = 1;
	
	public const CACHE_NO_MEMBERS_AND_CHANNELS = 2;
	
	public const CACHE_NO_MEMBERS_AND_CHANNELS_AND_ROLES = 3;
	
	public const CACHE_MINIMUM = 10;
	
	
	public const TYPE_MEMBERS = 1;
	
	public const TYPE_CHANNEL = 2;
	
	public const TYPE_ROLES = 3;
	
	public const TYPE_AUDIT_LOG = 4;
	
	public const TYPE_BAN_LIST = 5;
	
	public static function canCache(int $action): bool {
		return (Discord::$cacheLevel < $action);
	}
}