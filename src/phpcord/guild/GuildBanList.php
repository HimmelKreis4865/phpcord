<?php

namespace phpcord\guild;

class GuildBanList {
	/** @var GuildBanEntry[] $bans */
	protected $bans = [];

	public function __construct(array $bans = []) {
		$this->bans = array_filter($bans, function($ban) {
			return ($ban instanceof GuildBanEntry);
		});
	}

	/**
	 * Returns a list of all loaded bans in cache
	 *
	 * @api
	 *
	 * @return GuildBanEntry[]
	 */
	public function getBans(): array {
		return $this->bans;
	}

	/**
 	 * Adds a ban to the cache, won't affect the discord server!
	 *
	 * @internal don't use this for your own api
	 *
	 * @param GuildBanEntry $entry
	 */
	public function addBan(GuildBanEntry $entry) {
		if (isset($this->bans[$entry->getUser()->getId()])) return;
		$this->bans[$entry->getUser()->getId()] = $entry;
	}

	/**
	 * Removes a ban from the cache, won't affect the discord server!
	 *
	 * @internal don't use this for your own api
	 *
	 * @param GuildBanEntry|string $entry
	 */
	public function removeBan($entry) {
		if ($entry instanceof GuildBanEntry) $entry = $entry->getUser()->getId();
		if (isset($this->bans[$entry])) unset($this->bans[$entry]);
	}

	/**
	 * Returns a ban by member id or null if not banned
	 *
	 * @api
	 *
	 * @param string $id
	 *
	 * @return GuildBanEntry|null
	 */
	public function getBan(string $id): ?GuildBanEntry {
		return @$this->bans[$id];
	}
}


