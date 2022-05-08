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

namespace phpcord\channel\types\guild;

use InvalidArgumentException;
use phpcord\async\completable\Completable;
use phpcord\channel\ChannelBuilder;
use phpcord\channel\ChannelTypes;
use phpcord\channel\GuildChannel;
use phpcord\utils\Utils;

class GuildCategoryChannel extends GuildChannel {
	
	/**
	 * @param ChannelBuilder $builder
	 *
	 * @return Completable<GuildChannel>
	 */
	public function createChannel(ChannelBuilder $builder): Completable {
		if ($builder->getType() === ChannelTypes::GUILD_CATEGORY()) throw new InvalidArgumentException('Cannot put a category channel inside a category');
		$builder->setParent($this->getId());
		return $this->getGuild()->createChannel($builder);
	}
	
	public static function fromArray(array $array): ?self {
		if (!Utils::contains($array, 'id', 'guild_id', 'name', 'position')) return null;
		return new GuildCategoryChannel($array['id'], $array['guild_id'], $array['name'], $array['position'], null, $array['permission_overwrites'] ?? []);
	}
}