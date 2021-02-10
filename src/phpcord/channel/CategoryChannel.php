<?php

namespace phpcord\channel;

use phpcord\guild\GuildChannel;

class CategoryChannel extends GuildChannel {
	/**
	 * Returns the category type of the category
	 *
	 * @internal
	 *
	 * @return ChannelType
	 */
	public function getType(): ChannelType {
		return new ChannelType(ChannelType::TYPE_CATEGORY);
	}
}