<?php

namespace phpcord\channel;

use phpcord\guild\GuildChannel;

class CategoryChannel extends GuildChannel {
	public function getType(): ChannelType {
		return new ChannelType(ChannelType::TYPE_CATEGORY);
	}
}


